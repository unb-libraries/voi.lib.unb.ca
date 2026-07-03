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
