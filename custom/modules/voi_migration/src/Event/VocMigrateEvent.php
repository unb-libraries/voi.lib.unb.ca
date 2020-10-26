<?php

namespace Drupal\voi_migration\Event;

use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class VocMigrateEvent implements EventSubscriberInterface {

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
      case 'd7_taxonomy_vocabulary':
        $name = trim($row->getSourceProperty('name'));

        if ($name == "Type d'article") {
          $row->setSourceProperty('name', 'Article Type');
        }

        break;
    }
  }

}
