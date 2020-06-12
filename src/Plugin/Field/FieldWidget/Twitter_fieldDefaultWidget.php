<?php

namespace Drupal\twitter_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field widget "twitter_field_default".
 *
 * @FieldWidget(
 *   id = "twitter_field_default",
 *   label = @Translation("Twitter field default"),
 *   field_types = {
 *     "twitter_field",
 *   }
 * )
 */
class Twitter_fieldDefaultWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    // $item is where the current saved values are stored.
    $item =& $items[$delta];

    $element += [
      '#type' => 'fieldset',
    ];

    // Array keys in $element correspond roughly
    // to array keys in $item, which correspond
    // roughly to columns in the database table.
    $element['value'] = [
      '#title' => t('Value'),
      '#type' => 'textfield',
      '#description' => 'Use <b>@</b> to show tweets of the user or use <b>#</b> to show tweets with this hashtag.',
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->value) ? $item->value : '',
    ];
    $element['count'] = [
      '#title' => t('Count'),
      '#type' => 'textfield',
      '#description' => 'Number of tweets that will be shown.',
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->count) ? $item->count : '3',
    ];

    return $element;

  }

}
