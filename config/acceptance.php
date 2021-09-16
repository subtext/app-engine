<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

use Psr\Container\ContainerInterface;
use Subtext\AppEngine\Services\Database;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

use function DI\factory;

return [
    'db.dsn' => 'mysql:dbname=engine-db;host=mysql',
    'db.user' => 'root',
    'db.pass' => 'password',
    ContainerInterface::class => DI\factory(
        function (ContainerInterface $c) {
            return $c;
        }
    ),
    Request::class => factory([Request::class, 'createFromGlobals']),
    RequestContext::class => factory(
        function (ContainerInterface $c) {
            $context = new RequestContext();
            $context->fromRequest($c->get(Request::class));

            return $context;
        }
    ),
    Router::class => factory(
        function (ContainerInterface $c) {
            $locator = new FileLocator([dirname(__DIR__)]);
            $loader = new PhpFileLoader($locator);
            $router = new Router(
                $loader,
                'config/routes.php',
                [],
                $c->get(RequestContext::class)
            );

            return $router;
        }
    ),
    Database::class => factory(
        function (ContainerInterface $c) {
            return Database::getInstance(
                $c->get('db.dsn'),
                $c->get('db.user'),
                $c->get('db.pass')
            );
        }
    ),
];
