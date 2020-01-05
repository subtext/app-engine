<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Loader\FilesystemLoader;

return [
    'twig.template_path' => dirname(__DIR__) . '/src/twig/templates',
    'twig.cache_path' => dirname(__DIR__) . '/src/twig/cache',
    ContainerInterface::class => DI\factory(
        function (ContainerInterface $c) {
            return $c;
        }
    ),
    Request::class => DI\factory([Request::class, 'createFromGlobals']),
    LoaderInterface::class => DI\create(FilesystemLoader::class)->constructor(DI\get('twig.template_path')),
    Environment::class => DI\factory(
        function (ContainerInterface $c) {
            return new Environment($c->get(LoaderInterface::class), ['cache' => $c->get('twig.cache_path')]);
        }
    )
];
