<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

use function DI\factory;

return [
    'db.dsn' => 'mysql:dbname=demo;host=mysql',
    'db.user' => 'root',
    'db.pass' => 'password',
    ContainerInterface::class => DI\factory(
        function (ContainerInterface $c) {
            return $c;
        }
    ),
    LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Logger('debug');
        $logger->pushHandler(new Monolog\Handler\FingersCrossedHandler(
            new StreamHandler(__DIR__ . '/app.log'),
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
