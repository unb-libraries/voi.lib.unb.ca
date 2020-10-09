<?php

/**
 * @file
 * Contains refresh-doc.php.
 *
 * Refresh all documents.
 */

// Search for all document node ids.
$nids = Drupal::entityQuery('node')
  ->condition('type', 'document')->execute();

// Load and save all composites.
foreach ($nids as $nid) {
  $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
  $node->field_article_contents->format = 'unb_libraries';
  $node->save();
}
