<?php

namespace Drupal\voi_access\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\user\Entity\User;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events and restrict access to user.pass route.
 */
class VoiAccessRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Restrict user admin routes.
    $deny_routes = [
      'user.pass',
      'user.register',
      'user.reset',
      'entity.user.edit_form',
    ];

    // Deny access to non-admins.
    foreach ($deny_routes as $deny_route) {
      if ($route = $collection->get($deny_route)) {
        $route->setRequirement('_permission', 'administer_users');
      }
    }

    // Redirect taxonomy term cannonical route to custom controller.
    if ($route = $collection->get('entity.taxonomy_term.canonical')) {
      $route->setDefault('_controller', '\Drupal\voi_access\Controller\TermController::searchByTerm');
    }
  }

}
