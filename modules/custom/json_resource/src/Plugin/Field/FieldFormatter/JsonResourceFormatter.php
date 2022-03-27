<?php

namespace Drupal\json_resource\Plugin\Field\FieldFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\json_resource\Entity\JsonResource;

/**
 * Implementation of the 'json_resource' formatter.
 *
 * @FieldFormatter(
 *   id = "json_resource",
 *   label = @Translation("JSON Resource"),
 *   field_types = {
 *     "json_resource"
 *   }
 * )
 */
class JsonResourceFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = $item->getValue();
      if (!empty($value['target_id'])) {
        $entity = JsonResource::load($value['target_id']);
        $access = $this->checkAccess($entity);
        // Add the access result's cacheability, ::view() needs it.
        $item->_accessCacheability = CacheableMetadata::createFromObject($access);
        if ($access->isAllowed()) {
          $attributes = [
            'data-json-editor' => '1',
            'data-json-editor-options' => Json::encode([
              'mode' => 'view',
            ]),
            'data-json-editor-value' => $entity->json->value,
          ];

          $elements[$delta] = [
            '#theme' => 'json_resource',
            '#attached' => [
              'library' => 'json_resource/json-resource',
            ],
            '#attributes' => $attributes,
          ];
        }
      }
    }

    return $elements;
  }

}
