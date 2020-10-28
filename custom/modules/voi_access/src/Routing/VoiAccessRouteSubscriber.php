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

    // Get current user account object.
    $account = User::load(\Drupal::currentUser()->id());

    // Only restrict for non-admin users.
    if (!$account->hasRole('administrator')) {

      // Deny access to non-admins.
      foreach ($deny_routes as $deny_route) {
        if ($route = $collection->get($deny_route)) {
          $route->setRequirement('_access', 'FALSE');
        }
      }
    }
  }

}