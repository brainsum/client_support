# Client Support module

This module provides an extensible way for providing support for users.
Note, currently we display the 'Client support' link only on
the admin toolbar by default.

## Usage

* Implement your way of providing support. This can be a custom contact
form, a node, or anything with a route.
* Implement a plugin under the
```Drupal\your_module\Plugin\SupportIntegration``` namespace extending
the ```Drupal\client_support\Component\SupportIntegrationBase``` class.
* Go to ```admin/config/client-support/client-support-settings``` and
set your plugin.

That's it, now the 'Client support' link should be visible and it should
redirect to the set up route.
As an example you can take a look at the example module
```client_support_contact_form```.

## TODOs

* Support non-toolbar display.
* Add a more robust way of handling plugins instead of simple redirects.
* Add modes for opening the client support (e.g modal window).
* Tests.
* Add more default plugins.

After it's fleshed out, we plan on creating a project page for it on
drupal.org.
