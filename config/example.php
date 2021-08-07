<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
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
    'twig.cache_path' => false,
    ContainerInterface::class => factory(
        function (ContainerInterface $c) {
            return $c;
        }
    ),
    LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Logger('debug');
        $logger->pushHandler(new Monolog\Handler\FingersCrossedHandler(Logger::DEBUG));

        return $logger;
    }),
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
    LoaderInterface::class => create(FilesystemLoader::class)
        ->constructor(DI\get('twig.template_path')),
    Environment::class => factory(
        function (ContainerInterface $c) {

            return new Environment(
                $c->get(LoaderInterface::class),
                ['cache' => $c->get('twig.cache_path'), 'debug' => true]
            );
        }
    )
];
