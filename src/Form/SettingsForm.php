<?php

namespace Drupal\client_support\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a settings form for the module.
 *
 * @package Drupal\client_support\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      'client_support.settings',
    ];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'client_support_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\client_support\Component\SupportIntegrationManager $manager */
    $manager = \Drupal::service('plugin.manager.support_integration');
    $plugins = $manager->getDefinitions();

    $options = [];

    foreach ($plugins as $plugin) {
      $options[$plugin['id']] = $plugin['title'];
    }

    $default = $this->config('client_support.settings')->get('settings.integration_plugin');

    $form['integration_plugins'] = [
      '#type' => 'select',
      '#title' => $this->t('Support plugin'),
      '#options' => $options,
      '#default_value' => empty($default) ? NULL : $default,
      '#required' => TRUE,
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $settings = $this->config('client_support.settings');
    $settings->set('settings.integration_plugin', $form_state->getValue(['integration_plugins']));
    $settings->save();

    parent::submitForm($form, $form_state);
  }

}
