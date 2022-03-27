<?php

namespace Drupal\json_resource\Routing;

use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

/**
 * Subscriber for JSON Resource entity forms.
 */
class JsonResourceRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('entity.json_resource.canonical')) {
      $route->addDefaults(['_controller' => '\Drupal\json_resource\Controller\JsonResourceViewController::view']);
    }
  }

}
