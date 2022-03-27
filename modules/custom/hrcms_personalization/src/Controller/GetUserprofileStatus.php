<?php

namespace Drupal\hrcms_personalization\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/*
 * Class to update User on Notification.
 */
class GetUserprofileStatus extends ControllerBase {

  /*
   * Function to call the service UpdateArticlesNLP.
   */
  public function callGetUserprofileStatus() {
    $userProfile = User::load(\Drupal::currentUser()->id());
    $user_status = $userProfile->field_notification->value;
    return new JsonResponse($user_status);
  }
}
