<?php

namespace Drupal\voi_migration\Event;

use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class PageMigrateEvent implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::PREPARE_ROW][] = ['onPrepareRow', 0];
    return $events;
  }

  /**
   * React to a new row.
   *
   * @param \Drupal\migrate_plus\Event\MigratePrepareRowEvent $event
   *   The prepare-row event.
   */
  public function onPrepareRow(MigratePrepareRowEvent $event) {
    $row = $event->getRow();
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Act depending on migration.
    switch ($migration_id) {
      // Page nodes.
      case 'd7_node_complete:page':
        $title = trim($row->getSourceProperty('title'));

        switch ($title) {
          case "About Us":
            $value = file_get_contents('/app/html/modules/custom/voi_migration/data/pages/about.html');
            break;

          case "Qui sommes-nous?":
            $value = file_get_contents('/app/html/modules/custom/voi_migration/data/pages/about-fr.html');
            break;

          case "What is Computer-Assisted Textual Analysis?":
            $value = file_get_contents('/app/html/modules/custom/voi_migration/data/pages/what.html');
            break;

          case "Qu'est-ce que l'analyse de données textuelles?":
            $value = file_get_contents('/app/html/modules/custom/voi_migration/data/pages/what-fr.html');
            break;

          default:
            $value = $row->getSourceProperty('body')[0]['value'];
            break;
        }

        $body = [
          'value' => $value,
          'format' => 'full_html',
        ];

        $row->setSourceProperty('body', $body);
        break;
    }
  }

}
