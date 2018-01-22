<?php

namespace Drupal\kees_twitter_field\Plugin\Field\FieldType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;

/**
 * Field type "kees_twitter_field".
 *
 * @FieldType(
 *   id = "kees_twitter_field",
 *   label = @Translation("Twitter field"),
 *   description = @Translation("Kees Twitter field."),
 *   category = @Translation("Kees fields"),
 *   default_widget = "kees_twitter_field_default",
 *   default_formatter = "kees_twitter_field_default",
 * )
 */
class Kees_twitter_fieldItem extends FieldItemBase implements FieldItemInterface {

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
