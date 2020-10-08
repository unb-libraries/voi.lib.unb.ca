#!/bin/sh

# Enable migration modules.
printf "\n\n[Migration and setup for voi.lib.unb.ca]\n\n"
printf "\n\nEnabling migration modules...\n\n"
drush en --yes voi_migration

# Copy files required for migration of static pages.
printf "\n\nCopying static page files...\n\n"
mkdir /app/html/sites/default/files/inline-images/
cp /app/html/modules/custom/voi_migration/data/pages/img/* /app/html/sites/default/files/inline-images/
chown nginx:nginx /app/html/sites/default/files/inline-images/

# Run selected migrations.
printf "\n\nRunning Drupal migrations...\n\n"
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
drush migrate-import d7_node_complete:document
drush migrate-import d7_url_alias

# Format article content text.
printf "\n\nFormatting article content text...\n\n"
drush scr /app/html/modules/custom/voi_migration/scripts/format-doc.php

# Import UI translations.
printf "\n\nImporting UI translations...\n\n"
drush langimp --langcode=fr /app/html/modules/custom/voi_migration/config/lang/all-fr.po

# Disable migration modules.
printf "Disabling migration modules...\n\n"
drush -y pmu migrate
