<?php

namespace Drupal\twitter_field\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TwitterSettingsForm.
 *
 * @package Drupal\twitter_field\Form
 */
class TwitterSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {

    return [
      'twitter_field.twitter_api',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {

    return 'twitter_api';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('twitter_field.twitter_api');
    $form['oauth_access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Oauth Access Token'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('oauth_access_token'),
    ];
    $form['oauth_access_token_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Oauth Access Token Secret'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('oauth_access_token_secret'),
    ];
    $form['consumer_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Key'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('consumer_key'),
    ];
    $form['consumer_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer Secret'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('consumer_secret'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);

    $this->config('twitter_field.twitter_api')
      ->set('oauth_access_token', $form_state->getValue('oauth_access_token'))
      ->set('oauth_access_token_secret', $form_state->getValue('oauth_access_token_secret'))
      ->set('consumer_key', $form_state->getValue('consumer_key'))
      ->set('consumer_secret', $form_state->getValue('consumer_secret'))
      ->save();
  }

}
