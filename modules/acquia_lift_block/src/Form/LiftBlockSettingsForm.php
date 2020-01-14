<?php

namespace Drupal\acquia_lift_block\Form;

use Drupal\acquia_lift\Form\AdminSettingsForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Extend Acquia Lift configures settings.
 */
Class LiftBlockSettingsForm extends AdminSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('acquia_lift.settings');

    $form = parent::buildForm($form, $form_state);

    $form['lift_block'] = [
      '#title' => $this->t('Acquia Lift Block Credential'),
      '#type' => 'details',
      '#open' => FALSE,
      '#weight' => 0,
    ];

    $form['lift_block']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('Your Lift subscription\'s API Key.'),
      '#default_value' => $config->get('credential.api_key'),
      '#required' => TRUE,
    ];

    $form['lift_block']['secret_key'] = [
      '#type' => 'password',
      '#title' => $this->t('Secret Key'),
      '#description' => $this->t('Your Lift subscription\'s Secret Key.'),
      '#default_value' => $config->get('credential.secret_key'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('acquia_lift.settings')
      ->set('credential.api_key', $form_state->getValue('api_key'))
      ->set('credential.secret_key', $form_state->getValue('secret_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
