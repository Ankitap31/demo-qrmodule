<?php

namespace Drupal\hrcms_personalization\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\user\Entity\User;

/*
 * Class to update User on Notification.
 */
class GetDefaultCountry extends ControllerBase {


  public function callGetDefaultCountry($nid) {
		$node = \Drupal\node\Entity\Node::load($nid);
		$country = $node->get('field_country')->getValue();
		$country_id = $country[0]['target_id'];
		$value = $country_id. ',' .$nid;
    return new JsonResponse($value);
  }
}
