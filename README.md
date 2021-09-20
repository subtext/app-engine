# Subtext App Engine

## A tiny framework for building PHP based web applications.

### Installation
```shell
composer require subtext/app-engine
```

### Provisioning
Create a directory named "config" in the root project directory. Copy the 
example.php and routes files from the config directory in the app-engine to the 
newly created config. The example.php file contains a sample PHP-DI container
configuration. Edit this file to suit your needs. The example file uses the Twig
template system. In order to use this, you would need to the Twig libraries with
Composer. For more information see https://php-di.org/doc/

Edit the routes.php file to add a controller for any URL you want to create in
your web application. The most basic addition is to add a controller for the home
page of the application, like so:
```php
$routes->add('root', '/')->controller(Controllers\HomeController::class);
```
You would then simply need to create the HomeController class extending from the
Subtext\Base\Controller class. Add models and views as necessary to create any
dependencies for your controllers as they will be the primary means of manipulating
the application. For more information on configuring routes see: 
https://symfony.com/doc/4.3/components/routing.html