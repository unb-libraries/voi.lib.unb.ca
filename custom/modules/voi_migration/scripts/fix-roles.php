<?php

/**
 * @file
 * Contains fix-roles.php.
 *
 * Update roles after migration.
 */

use Drupal\user\Entity\Role;

$role_names = [
  'record_editor',
];

$permissions = [
  'create terms in article_type',
  'create terms in language',
  'create terms in newspapers',
  'edit terms in article_type',
  'edit terms in language',
  'edit terms in newspapers',
  'delete terms in article_type',
  'delete terms in language',
  'delete terms in newspapers',
];

// Assign the listed permissions to the listed roles.
foreach ($role_names as $role_name) {
  $role_object = Role::load($role_name);

  foreach ($permissions as $permission) {
    $role_object->grantPermission($permission);
    $role_object->save();
  }
}
