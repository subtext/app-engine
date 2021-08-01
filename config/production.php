<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Loader\FilesystemLoader;
use function DI\create;
use function DI\factory;

return [
    'twig.template_path' => dirname(__DIR__) . '/src/twig/templates',
    'twig.cache_path' => dirname(__DIR__) . '/src/twig/cache',
    ContainerInterface::class => DI\factory(
        function (ContainerInterface $c) {
            return $c;
        }
    ),
    Request::class => DI\factory([Request::class, 'createFromGlobals']),
    RequestContext::class => DI\factory(
        function (ContainerInterface $c) {
            $context = new RequestContext();
            $context->fromRequest($c->get(Request::class));

            return $context;
        }
    ),
    Router::class => DI\factory(
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
    LoaderInterface::class => DI\create(FilesystemLoader::class)->constructor(DI\get('twig.template_path')),
    Environment::class => DI\factory(
        function (ContainerInterface $c) {
            return new Environment($c->get(LoaderInterface::class), ['cache' => $c->get('twig.cache_path')]);
        }
    )
];
