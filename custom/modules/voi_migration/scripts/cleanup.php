<?php

/**
 * @file
 * Contains cleanup.php.
 *
 * Clean-up unused vocabularies, content types, etc, after migration.
 */

use Drupal\taxonomy\Entity\Vocabulary;

// Delete Article content type.
$content_type = \Drupal::entityTypeManager()
  ->getStorage('node_type')
  ->load('article');

if ($content_type) {
  $content_type->delete();
}

// Delete Tags vocabulary.
$vocab = Vocabulary::load('tags');

if ($vocab) {
  $vocab->delete();
}
