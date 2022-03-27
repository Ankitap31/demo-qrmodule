<?php

namespace Drupal\json_resource\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\json_resource\Entity\JsonResource;

/**
 * Plugin implementation of the 'JSON Resource' widget.
 *
 * @FieldWidget(
 *   id = "json_resource",
 *   label = @Translation("JSON Resource"),
 *   field_types = {
 *     "json_resource"
 *   }
 * )
 */
class JsonResourceWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $attributes = [
      'data-json-editor' => 1,
      'data-json-editor-options' => Json::encode([
        'mode' => 'code',
        'modes' => ['tree', 'code'],
      ]),
      'data-json-editor-mode' => 'code',
      'class' => 'form-item',
    ];

    $item = $items->get($delta);
    $entity_value = $item->getValue();
    if (!empty($entity_value['target_id'])) {
      $json_resource = JsonResource::load($entity_value['target_id']);
      $json_value = $json_resource->json->value;
    }
    if (!empty($json_value)) {
      $attributes['data-json-editor-mode'] = 'tree';
    }

    $element['json_editor'] = [
      '#theme' => 'json_resource',
      '#attached' => [
        'library' => 'json_resource/json-resource',
      ],
      '#attributes' => $attributes,
    ];

    $element['target_id'] = [
      '#type' => 'hidden',
      '#default_value' => isset($entity_value['target_id']) ? $entity_value['target_id'] : NULL,
    ];

    $element['value'] = [
      '#prefix' => '<div class="json-editor-widget-textarea">',
      '#type' => 'textarea',
      '#default_value' => isset($json_value) ? $json_value : '',
      '#suffix' => '</div>',
    ];

    $element['#type'] = 'item';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $new_values = [];
    foreach ($values as $value) {
      if (empty($value['target_id']) && !empty($value['value'])) {
        $json = Json::decode($value['value']);
        if (!empty($json)) {
          $new_value = $value;
          $new_value['entity'] = JsonResource::create([
            'json' => $value['value'],
            'field_name' => $this->fieldDefinition->getName(),
          ]);
          unset($new_value['target_id']);
          $new_values[] = $new_value;
        }
      }
      elseif (!empty($value['target_id'])) {
        $new_value = $value;
        $json_resource = JsonResource::load($value['target_id']);
        if (strcmp($json_resource->json->value, $value['value']) !== 0) {
          $json = Json::decode($value['value']);
          if (!empty($json)) {
            $json_resource->json = $value['value'];
          }
          else {
            $json_resource->json = NULL;
          }
          $json_resource->setNewRevision();

          $json_resource->revision_log = 'Created revision';
          $json_resource->setRevisionCreationTime(\Drupal::time()->getRequestTime());
          $json_resource->setRevisionUserId(\Drupal::currentUser()->id());
          $json_resource->setRevisionJson($json_resource->json->value);
          $json_resource->isDefaultRevision(TRUE);

          $json_resource->save();

          $new_value['entity'] = $json_resource;
        }
        $new_values[] = $new_value;
      }
    }

    return $new_values;
  }

}
