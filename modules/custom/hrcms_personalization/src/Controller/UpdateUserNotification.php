<?php

namespace Drupal\hrcms_personalization\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Class to update User on Notification.
 */
class UpdateUserNotification extends ControllerBase {

  /*
   * Function to call the service UpdateArticlesNLP.
   */
  public function callUpdateNotification() {
    $userProfile = User::load(\Drupal::currentUser()->id());
  	$userProfile->set('field_notification', 0);
    $userProfile->save();

    return new JsonResponse($userProfile);
  }
}
