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
use Subtext\AppEngine\Controller;
use Subtext\AppEngine\Controllers\Loader;
use Symfony\Component\HttpFoundation\Response;

use function DI\factory;

return [
    Loader::class => factory(function () {
        return Loader::create(
            new class () extends Controller {
                public function execute(mixed $params): Response
                {
                    return new Response('Hello World');
                }
            }, []
        );
    }),
    LoggerInterface::class => factory(function(ContainerInterface $c) {
        $logger = new Logger('debug');
        $logger->pushHandler(new FingersCrossedHandler(
            new StreamHandler('php://stderr'),
            Level::Debug
        ));

        return $logger;
    }),
];
