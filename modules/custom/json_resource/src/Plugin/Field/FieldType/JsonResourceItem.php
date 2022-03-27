<?php

namespace Drupal\json_resource\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

/**
 * Plugin implementation of the 'JSON Resource' field type.
 *
 * @FieldType(
 *   id = "json_resource",
 *   label = @Translation("JSON Resource"),
 *   description = @Translation("This field stores the ID of a json_resource as an integer value."),
 *   category = @Translation("Reference"),
 *   default_widget = "json_resource",
 *   default_formatter = "json_resource",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 *   constraints = {"ReferenceAccess" = {}}
 * )
 */
class JsonResourceItem extends EntityReferenceItem {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'target_type' => 'json_resource',
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'target_id' => [
          'description' => 'The ID of the file entity.',
          'type' => 'int',
          'unsigned' => TRUE,
        ],
      ],
      'indexes' => [
        'target_id' => ['target_id'],
      ],
    ];
  }

}
