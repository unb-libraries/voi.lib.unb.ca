#!/bin/sh

# Enable migration module.
drush en --yes voi_migration

# Copy files required for migration of static pages.
mkdir /app/html/sites/default/files/inline-images/
cp /app/html/modules/custom/voi_migration/data/pages/img/* /app/html/sites/default/files/inline-images/

# Run selected migrations.
drush migrate-import language
drush migrate-import d7_user_role
drush migrate-import d7_user
drush migrate-import d7_node_type
drush migrate-import d7_taxonomy_vocabulary
drush migrate-import d7_taxonomy_term:article_type
drush migrate-import d7_taxonomy_term:newspapers
drush migrate-import d7_taxonomy_term:language
drush migrate-import d7_taxonomy_term_localized_translation
drush migrate-import d7_taxonomy_term_translation
drush migrate-import d7_node_complete:page
# drush migrate-import d7_node_complete:document
drush migrate-import d7_url_alias
