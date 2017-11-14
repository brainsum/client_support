<?php

namespace Drupal\client_support\Handler;

use Drupal\client_support\Component\SupportIntegrationManager;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ToolbarHandler.
 *
 * Implements logic for rendering the toolbar item for the module.
 *
 * @package Drupal\client_support\Handler
 */
class ToolbarHandler implements ContainerInjectionInterface {


  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * Config for the module.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $supportSettings;

  /**
   * The plugin manager for the Client Support module.
   *
   * @var \Drupal\client_support\Component\SupportIntegrationManager
   */
  protected $supportPluginManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('toolbar.menu_tree'),
      $container->get('config.factory'),
      $container->get('current_user'),
      $container->get('plugin.manager.support_integration')
    );
  }

  /**
   * ToolbarHandler constructor.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menuLinkTree
   *   The menu link tree service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user.
   * @param \Drupal\client_support\Component\SupportIntegrationManager $supportPluginManager
   *   The plugin manager for the Client Support module.
   */
  public function __construct(
    MenuLinkTreeInterface $menuLinkTree,
    ConfigFactoryInterface $configFactory,
    AccountProxyInterface $account,
    SupportIntegrationManager $supportPluginManager
  ) {
    $this->menuLinkTree = $menuLinkTree;
    $this->account = $account;
    $this->supportSettings = $configFactory->get('client_support.settings');
    $this->supportPluginManager = $supportPluginManager;
  }

  /**
   * Hook bridge.
   *
   * @return array
   *   The devel toolbar items render array.
   *
   * @see hook_toolbar()
   */
  public function toolbar() {
    // Don't display anything in the toolbar if there are no plugins,
    // or there are, but none are selected.
    // The user also must have proper access permissions.
    if (!$this->access()) {
      return [];
    }

    $items['client_support'] = [
      '#type' => 'toolbar_item',
      '#weight' => 999,
      'tab' => [
        '#type' => 'link',
        '#title' => $this->t('Client support'),
        '#url' => Url::fromRoute('client_support.toolbar'),
        '#attributes' => [
          'title' => $this->t('Client support'),
          'class' => [
            'toolbar-icon',
            'toolbar-icon-client-support',
          ],
        ],
      ],
      '#wrapper_attributes' => [
        'class' => [
          'client-support-toolbar-tab',
        ],
      ],
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
      '#attached' => [
        'library' => 'client_support/toolbar',
      ],
    ];

    return $items;
  }

  /**
   * Lazy builder callback for the menu toolbar.
   *
   * @return array
   *   The renderable array rapresentation of the devel menu.
   */
  public function lazyBuilder() {
    $parameters = new MenuTreeParameters();
    $parameters->onlyEnabledLinks()->setTopLevelOnly();

    $tree = $this->menuLinkTree->load('client_support', $parameters);

    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuLinkTree->transform($tree, $manipulators);

    $build = $this->menuLinkTree->build($tree);

    CacheableMetadata::createFromRenderArray($build)->applyTo($build);

    return $build;
  }

  /**
   * Access check for the handler.
   *
   * The module has to be set up. This means that plugins must exist
   * and a plugin has to be set.
   * The user must have proper permissions.
   *
   * @return bool
   *   TRUE, if the user can access it, FALSE otherwise.
   */
  protected function access() {
    $plugins = $this->supportPluginManager->getDefinitions();
    $currentPlugin = $this->supportSettings->get('settings.integration_plugin');

    return
      !(empty($plugins) || NULL === $currentPlugin)
      && $this->account->hasPermission('access client support');
  }

}
