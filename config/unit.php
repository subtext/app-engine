<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

use function DI\factory;

return [
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
    )
];
