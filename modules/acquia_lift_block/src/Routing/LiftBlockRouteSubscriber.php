<?php

namespace Drupal\acquia_lift_block\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class LiftBlockRouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('acquia_lift.admin_settings_form')) {
      $route->setDefault('_form', 'Drupal\acquia_lift_block\Form\LiftBlockSettingsForm');
    }
  }
}
