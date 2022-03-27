<?php

namespace Drupal\json_resource\Entity;

use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\json_resource\JsonResourceInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the JSON Resource entity.
 *
 * @ingroup json_resource
 *
 * @ContentEntityType(
 *   id = "json_resource",
 *   label = @Translation("JSON Resource"),
 *   handlers = {
 *     "access" = "Drupal\json_resource\JsonResourceAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "json_resource",
 *   revision_table = "json_resource_revision",
 *   admin_permission = "administer json_resource entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "revision" = "revision_id",
 *     "published" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *     "revision_json" = "revision_json"
 *   },
 *   links = {
 *     "canonical" = "/json_resource/{json_resource}"
 *   },
 * )
 */
class JsonResource extends RevisionableContentEntityBase implements JsonResourceInterface {
  use EntityChangedTrait;
  use EntityPublishedTrait;
  use EntityOwnerTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(new TranslatableMarkup('User ID'))
      ->setSetting('target_type', 'user')
      ->setTranslatable($entity_type->isTranslatable())
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner');

    $fields['json'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('JSON'))
      ->setRequired(TRUE)
      ->setSettings(['text_processing' => 0])
      ->setDefaultValue('')
      ->setDisplayOptions('view', ['label' => 'above', 'type' => 'string'])
      ->setDisplayOptions('form', ['type' => 'string_textarea'])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Published'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields[$entity_type->getRevisionMetadataKey('revision_json')] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Revision json'))
      ->setDescription(t('The time that the current revision was created.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

  /**
   * Implements JsonResourceInterface::getRevisionJson().
   */
  public function getRevisionJson() {
    return $this->{$this->getEntityType()->getRevisionMetadataKey('revision_json')}->value;
  }

  /**
   * Implements JsonResourceInterface::setRevisionJson().
   */
  public function setRevisionJson($value) {
    $this->{$this->getEntityType()->getRevisionMetadataKey('revision_json')}->value = $value;
    return $this;
  }

}
