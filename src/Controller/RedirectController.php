<?php

namespace Drupal\client_support\Controller;

use Drupal\client_support\Component\SupportIntegrationManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RedirectController.
 *
 * @package Drupal\client_support\Controller
 */
class RedirectController extends ControllerBase {

  /**
   * The plugin manager for the Client Support module.
   *
   * @var \Drupal\client_support\Component\SupportIntegrationManager
   */
  protected $supportPluginManager;

  /**
   * Config for the module.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $supportSettings;

  /**
   * Class resolver service.
   *
   * @var \Drupal\Core\DependencyInjection\ClassResolverInterface
   */
  protected $classResolver;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.support_integration'),
      $container->get('class_resolver')
    );
  }

  /**
   * RedirectController constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\client_support\Component\SupportIntegrationManager $supportPluginManager
   *   The plugin manager for the Client Support module.
   * @param \Drupal\Core\DependencyInjection\ClassResolverInterface $classResolver
   *   Class resolver service.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    SupportIntegrationManager $supportPluginManager,
    ClassResolverInterface $classResolver
  ) {
    $this->supportPluginManager = $supportPluginManager;
    $this->supportSettings = $configFactory->get('client_support.settings');
    $this->classResolver = $classResolver;
  }

  /**
   * Redirect handler.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   Return a response from a plugin.
   *
   * @throws \InvalidArgumentException
   */
  public function redirectHandler() {
    $plugins = $this->supportPluginManager->getDefinitions();

    $pluginId = $this->supportSettings->get('settings.integration_plugin');

    /** @var \Drupal\client_support\Component\SupportIntegrationInterface $pluginInstance */
    $pluginInstance = $this->classResolver
      ->getInstanceFromDefinition($plugins[$pluginId]['class']);

    return $pluginInstance->redirect();
  }

}
