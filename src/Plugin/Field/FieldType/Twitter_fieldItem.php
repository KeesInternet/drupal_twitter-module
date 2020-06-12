<?php

namespace Drupal\twitter_field\Plugin\Field\FieldType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;

/**
 * Field type "twitter_field".
 *
 * @FieldType(
 *   id = "twitter_field",
 *   label = @Translation("Twitter field"),
 *   description = @Translation("Twitter field."),
 *   category = @Translation("fields"),
 *   default_widget = "twitter_field_default",
 *   default_formatter = "twitter_field_default",
 * )
 */
class Twitter_fieldItem extends FieldItemBase implements FieldItemInterface {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $output = [];
    $output['columns']['value'] = [
      'type' => 'varchar',
      'length' => 255,
    ];
    $output['columns']['count'] = [
      'type' => 'varchar',
      'length' => 255,
    ];

    return $output;

  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Value'))
      ->setRequired(FALSE);
    $properties['count'] = DataDefinition::create('string')
      ->setLabel(t('Count'))
      ->setRequired(FALSE);

    return $properties;

  }

}
