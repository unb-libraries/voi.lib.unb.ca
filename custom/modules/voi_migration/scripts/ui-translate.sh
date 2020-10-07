#!/bin/sh

printf "\n\n[UI translation setup for voi.lib.unb.ca]\n\n"

# Import UI translations.
printf "\n\nImporting UI translations...\n\n"
drush langimp --langcode=fr /app/html/modules/custom/voi_migration/config/lang/all-fr.po
