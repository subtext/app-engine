<?php
/**
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    Request::class => factory([Request::class, 'createFromGlobals']),
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
