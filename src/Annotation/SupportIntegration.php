<?php

namespace Drupal\client_support\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Annotation for client support integrations.
 *
 * @package Drupal\client_support\Annotation
 *
 * @Annotation
 */
class SupportIntegration extends Plugin {

  /**
   * The unique ID of the plugin.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable title of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * Short description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
