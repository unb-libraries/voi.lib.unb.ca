<?php

/**
 * @file
 * Contains fix-roles.php.
 *
 * Update roles after migration.
 */

use Drupal\user\Entity\Role;

$roles = [
  'anonymous',
  'page_editor',
  'record_editor',
  'administrator',
];

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
  'access taxonomy overview',
];

$all_permissions = [
  'use text format unb_libraries',
  'flag document_data_bundle',
  'unflag document_data_bundle',
];

// Assign record editor permissions.
$role_object = Role::load('record_editor');

foreach ($record_permissions as $record_permission) {
  $role_object->grantPermission($record_permission);
  $role_object->save();
}

// Assign all other permissions.
foreach ($roles as $role) {
  $role_object = Role::load($role);

  foreach ($all_permissions as $permission) {
    $role_object->grantPermission($permission);
    $role_object->save();
  }
}
