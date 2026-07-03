<?php

/**
 * @file
 * Post update functions for VOI Core.
 */

/**
 * Delete flagging content so the flag module can be uninstalled.
 */
function voi_core_post_update_remove_flaggings(&$sandbox) {
  $entity_type_manager = \Drupal::entityTypeManager();

  // If the flag module (and its flagging entity type) is already gone there is
  // nothing to clean up. Keeps this hook safe to leave in place on future
  // deploys.
  if (!$entity_type_manager->hasDefinition('flagging')) {
    return t('Flagging entity type not present; nothing to remove.');
  }

  $storage = $entity_type_manager->getStorage('flagging');

  if (!isset($sandbox['ids'])) {
    $sandbox['ids'] = array_values($storage->getQuery()->accessCheck(FALSE)->execute());
    $sandbox['total'] = count($sandbox['ids']);
  }

  $batch = array_splice($sandbox['ids'], 0, 50);
  if (!empty($batch)) {
    $storage->delete($storage->loadMultiple($batch));
  }

  $sandbox['#finished'] = empty($sandbox['ids']) ? 1 : (1 - (count($sandbox['ids']) / max($sandbox['total'], 1)));

  return t('Removed @count flaggings so the flag module can be uninstalled.', ['@count' => $sandbox['total']]);
}
