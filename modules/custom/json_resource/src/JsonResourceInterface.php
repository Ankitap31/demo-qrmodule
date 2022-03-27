<?php

namespace Drupal\json_resource;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Contact entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup json_resource
 */
interface JsonResourceInterface extends
    ContentEntityInterface,
    EntityOwnerInterface,
    EntityChangedInterface,
    EntityPublishedInterface,
    RevisionLogInterface {

  /**
   * Get JSON.
   *
   * @return string
   *   JSON string
   */
  public function getRevisionJson();

  /**
   * Set JSON.
   *
   * @param string $value
   *   JSON string.
   */
  public function setRevisionJson($value);

}
