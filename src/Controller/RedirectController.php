<?php

namespace Drupal\client_support\Controller;

/**
 * Class RedirectController.
 *
 * @package Drupal\client_support\Controller
 */
class RedirectController {

  /**
   * Redirect handler.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   Return a response from a plugin.
   *
   * @throws \InvalidArgumentException
   */
  public function redirect() {
    // @todo: Dependency injection.
    /** @var \Drupal\client_support\Component\SupportIntegrationManager $manager */
    $manager = \Drupal::service('plugin.manager.support_integration');
    $plugins = $manager->getDefinitions();

    $pluginId = \Drupal::configFactory()->get('client_support.settings')->get('settings.integration_plugin');

    /** @var \Drupal\client_support\Component\SupportIntegrationInterface $pluginInstance */
    $pluginInstance = \Drupal::service('class_resolver')
      ->getInstanceFromDefinition($plugins[$pluginId]['class']);

    return $pluginInstance->redirect();
  }

}
