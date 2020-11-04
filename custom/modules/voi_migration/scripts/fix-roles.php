<?php

/**
 * @file
 * Contains fix-roles.php.
 *
 * Update roles after migration.
 */

use Drupal\user\Entity\Role;

$record_permissions = [
  'create terms in article_type',
  'create terms in language',
  'create terms in newspapers',
  'edit terms in article_type',
  'edit terms in language',
  'edit terms in newspapers',
  'delete terms in article_type',
  'delete terms in language',
  'delete terms in newspapers',
  'use text format unb_libraries',
];

$page_permissions = [
  'use text format unb_libraries',
];

// Assign record editor permissions.
$role_object = Role::load('record_editor');

foreach ($record_permissions as $record_permission) {
  $role_object->grantPermission($record_permission);
  $role_object->save();
}
