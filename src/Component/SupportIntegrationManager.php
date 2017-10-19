<?php

namespace Drupal\client_support\Component;

use Drupal\client_support\Annotation\SupportIntegration;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Class SupportIntegrationManager.
 *
 * @package Drupal\client_support\Component
 *
 * @see plugin_api
 * @see \Drupal\client_support\Annotation\SupportIntegration
 * @see \Drupal\client_support\Component\SupportIntegrationBase
 * @see \Drupal\client_support\Component\SupportIntegrationInterface
 */
class SupportIntegrationManager extends DefaultPluginManager {

  /**
   * Constructor for SupportIntegrationManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cacheBackend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cacheBackend, ModuleHandlerInterface $moduleHandler) {
    parent::__construct(
      'Plugin/SupportIntegration',
      $namespaces,
      $moduleHandler,
      SupportIntegrationInterface::class,
      SupportIntegration::class
    );

    $this->setCacheBackend($cacheBackend, 'support_integration_plugins');
    $this->alterInfo('support_integration_info');
  }

}
