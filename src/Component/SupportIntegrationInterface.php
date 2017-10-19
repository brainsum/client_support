<?php

namespace Drupal\client_support\Component;

/**
 * Interface SupportIntegrationInterface.
 *
 * @package Drupal\client_support\Component
 */
interface SupportIntegrationInterface {

  /**
   * Return a redirect response.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response.
   *
   * @throws \InvalidArgumentException
   */
  public function redirect();

}
