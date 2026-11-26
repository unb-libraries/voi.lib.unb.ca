<?php

/**
 * @file
 * Post update functions for VOI Core.
 */

/**
 * Truncate flagging storage so the flag module can be uninstalled.
 */
function voi_core_post_update_remove_flaggings(&$sandbox) {
  $entity_type_manager = \Drupal::entityTypeManager();

  // If the flag module (and its flagging entity type) is already gone there is
  // nothing to clean up. Keeps this hook safe to leave in place on future
  // deploys.
  if (!$entity_type_manager->hasDefinition('flagging')) {
    return t('Flagging entity type not present; nothing to remove.');
  }

  // The site has millions of flaggings, so deleting them through the entity
  // API (which fires hooks and search_api tracking per item) is far too slow
  // to complete within a deploy. The module and its tables are dropped on
  // uninstall anyway, so truncate the storage tables directly instead.
  $database = \Drupal::database();
  $definition = $entity_type_manager->getDefinition('flagging');
  $tables = [
    $definition->getBaseTable(),
    $definition->getDataTable(),
    // Aggregate counts maintained by the flag module.
    'flag_counts',
  ];

  foreach (array_filter($tables) as $table) {
    if ($database->schema()->tableExists($table)) {
      $database->truncate($table)->execute();
    }
  }

  return t('Truncated flagging storage so the flag module can be uninstalled.');
}

/**
 * Convert plain-text line breaks in document contents to HTML markup.
 *
 * Document bodies were migrated from D7 as plain text and later switched to
 * the unb_libraries (HTML) format, which has no line-break filter. Their
 * paragraphs therefore collapse into a single block, both when rendered and
 * in CKEditor 5. Wrap them in <p>/<br> once so the stored value is real HTML.
 */
function voi_core_post_update_document_contents_autop(&$sandbox) {
  $database = \Drupal::database();
  $data_table = 'node__field_article_contents';
  $revision_table = 'node_revision__field_article_contents';
  $batch_size = 500;

  if (!isset($sandbox['max'])) {
    $sandbox['max'] = (int) $database->select($revision_table, 'r')
      ->countQuery()->execute()->fetchField();
    $sandbox['last'] = 0;
    $sandbox['progress'] = 0;
    $sandbox['converted'] = 0;
  }

  $rows = $database->select($revision_table, 'r')
    ->fields('r', [
      'entity_id',
      'revision_id',
      'langcode',
      'delta',
      'field_article_contents_value',
    ])
    ->condition('revision_id', $sandbox['last'], '>')
    ->orderBy('revision_id')
    ->range(0, $batch_size)
    ->execute()
    ->fetchAll();

  // Revision ids may span more than one row (translations, deltas); finish the
  // last revision id in this batch so the "> last" cursor never skips rows.
  if (count($rows) === $batch_size) {
    $last_id = end($rows)->revision_id;
    $rows = array_merge(
      array_filter($rows, fn($row) => $row->revision_id != $last_id),
      $database->select($revision_table, 'r')
        ->fields('r', [
          'entity_id',
          'revision_id',
          'langcode',
          'delta',
          'field_article_contents_value',
        ])
        ->condition('revision_id', $last_id)
        ->execute()
        ->fetchAll()
    );
  }

  $nids = [];
  foreach ($rows as $row) {
    $sandbox['progress']++;
    $sandbox['last'] = max($sandbox['last'], $row->revision_id);
    $value = $row->field_article_contents_value;

    // Skip empty values and values that already carry block-level markup.
    if (trim((string) $value) === '' || preg_match('/<(p|br|div|table|ul|ol|h[1-6]|blockquote|pre)[\s>\/]/i', $value)) {
      continue;
    }

    $converted = _filter_autop($value);
    $keys = [
      'entity_id' => $row->entity_id,
      'langcode' => $row->langcode,
      'delta' => $row->delta,
    ];

    $database->update($revision_table)
      ->fields(['field_article_contents_value' => $converted])
      ->condition('revision_id', $row->revision_id)
      ->condition('entity_id', $keys['entity_id'])
      ->condition('langcode', $keys['langcode'])
      ->condition('delta', $keys['delta'])
      ->execute();

    // Only the default revision lives in the data table.
    $database->update($data_table)
      ->fields(['field_article_contents_value' => $converted])
      ->condition('revision_id', $row->revision_id)
      ->condition('entity_id', $keys['entity_id'])
      ->condition('langcode', $keys['langcode'])
      ->condition('delta', $keys['delta'])
      ->execute();

    $sandbox['converted']++;
    $nids[$row->entity_id] = $row->entity_id;
  }

  if ($nids) {
    \Drupal::entityTypeManager()->getStorage('node')->resetCache($nids);
  }

  $sandbox['#finished'] = empty($rows) || $sandbox['max'] == 0
    ? 1
    : min(0.99, $sandbox['progress'] / $sandbox['max']);

  if ($sandbox['#finished'] >= 1) {
    // Rendered output is cached; direct DB writes bypass cache invalidation.
    \Drupal\Core\Cache\Cache::invalidateTags(['node_list']);
    \Drupal::service('cache.render')->deleteAll();
    \Drupal::service('cache.page')->deleteAll();
    return t('Converted @count document content values to HTML paragraphs.', ['@count' => $sandbox['converted']]);
  }
}
