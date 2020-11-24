<?php

use Behat\Behat\Context\Context;
use Drupal\search_api\Entity\Index;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * Wait for $seconds.
   *
   * @When I wait :seconds
   */
  public function iWait($seconds) {
    sleep($seconds);
  }

  /**
   * Index Search API $index_id and wait $seconds.
   *
   * @When I re-index :index_id and wait :seconds
   */
  public function iReIndexAndWait($index_id, $seconds) {
    $index = Index::load($index_id);
    $index->indexItems();
    sleep($seconds);
  }

}
