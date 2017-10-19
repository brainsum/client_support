<?php

namespace Drupal\client_support_contact_form\Plugin\SupportIntegration;

use Drupal\client_support\Component\SupportIntegrationBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;

/**
 * Class ContactFormIntegration.
 *
 * @SupportIntegration(
 *   id="contact_form",
 *   title=@Translation("Contact Form Integration"),
 *   description=@Translation("Integration plugin for a core Contact Form.")
 * )
 *
 * @package Drupal\client_support_contact_form\Plugin\SupportIntegration
 */
class ContactFormIntegration extends SupportIntegrationBase {

  /**
   * Return a redirect response.
   *
   * @return \Drupal\Core\Routing\TrustedRedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response.
   *
   * @throws \InvalidArgumentException
   */
  public function redirect() {
    $routeName = 'entity.contact_form.canonical';
    $contactForm = 'support_form';

    $url = Url::fromRoute($routeName, [
      'contact_form' => $contactForm,
    ])->toString(TRUE)->getGeneratedUrl();

    return new TrustedRedirectResponse($url);
  }

}
