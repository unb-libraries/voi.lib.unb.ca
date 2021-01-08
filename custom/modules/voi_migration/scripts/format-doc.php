<?php

/**
 * @file
 * Contains format-doc.php.
 *
 * Set all documents to use unb_libraries text format in body.
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
