<?php
/**
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment as TemplateEnvironment;
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
    TemplateEnvironment::class => DI\factory(
        function (ContainerInterface $c) {
            return new TemplateEnvironment($c->get(LoaderInterface::class), ['cache' => $c->get('twig.cache_path')]);
        }
    )
];
