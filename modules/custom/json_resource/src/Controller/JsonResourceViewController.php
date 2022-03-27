<?php

namespace Drupal\json_resource\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Defines a controller to render the JSON Resource entity.
 */
class JsonResourceViewController extends EntityViewController {

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $json_resource, $view_mode = 'full') {
    return new JsonResponse(Json::decode($json_resource->json->value));
  }

}
