<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2025 All rights reserved
 * @license MIT
 */

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Subtext\AppEngine\Controllers\Factory;
use Subtext\AppEngine\Controllers\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

use function DI\factory;

return [
    Factory::class => factory(function (ContainerInterface $c) {
        return new Factory(function (string $class) use ($c) {
            if (!$c->has($class)) {
                throw new RouteNotFoundException();
            }
            return $c->get($class);
        });
    }),
    Loader::class => factory(function (
        Router $router,
        Request $request,
        Factory $factory
    ) {
        $params = Loader::resolveController($router, $request);
        $controller = ($params['_controller'] ?? null);
        if (!$controller) {
            throw new RouteNotFoundException();
        }
        return Loader::create($factory->get($controller), $params);
    }),
    LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Logger('debug');
        $logger->pushHandler(new FingersCrossedHandler(
            new StreamHandler('php://stderr'),
            Level::Debug
        ));

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
            return new Router(
                new PhpFileLoader(new FileLocator([dirname(__DIR__)])),
                'config/routes.php',
                [],
                $c->get(RequestContext::class)
            );
        }
    ),
];
