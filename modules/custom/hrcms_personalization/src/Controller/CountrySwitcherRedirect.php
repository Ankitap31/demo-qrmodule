<?php

namespace Drupal\hrcms_personalization\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;


/*
 * Class to update User on Notification.
 */
class CountrySwitcherRedirect extends ControllerBase {


  public function callCountrySwitcherRedirect($countryid, $nid) {

      $term_object = \Drupal\taxonomy\Entity\Term::load($countryid);
	  $node = \Drupal\node\Entity\Node::load($nid);
	  $category = $node->get('field_content_category')->getValue();
	  $category_id = $category[0]['target_id'];
	  
	  $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('field_content_category', $category_id, '=')
    ->condition('field_country', $countryid, '=');
	global $base_url;
	$nids = $query->execute();
	$nodeid = implode($nids);
	if ($nodeid){
    $absolute_url = $base_url."/node/".$nodeid;
	}
	else $absolute_url = $base_url."/node/".$nid;
    return new JsonResponse($absolute_url);
  }
}
