<?php

namespace Subtext\AppFactory;

use Psr\Container\ContainerInterface;
use Subtext\AppFactory\Base\Controller;
use Subtext\AppFactory\Controllers\ViewController;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class Application
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
final class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * Application constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    public function execute(): void
    {
        try {
            $controller = $this->getController();
            $response = $controller->execute();
            $response->send();
        } catch (Throwable $error) {
            $retreat = new Fallback($error);
            $retreat->failGracefully();
        }
    }

    private function getController(): Controller
    {
        switch (true) {
            case $this->request->isMethod('POST'):
                $controller = $this->container->get(ViewController::class);
                break;
            case $this->request->isMethod('GET'):
                $controller = $this->container->get(ViewController::class);
                break;
            case $this->request->isMethod('PUT'):
                $controller = $this->container->get(ViewController::class);
                break;
            case $this->request->isMethod('DELETE'):
                $controller = $this->container->get(ViewController::class);
                break;
            default:
                $controller = $this->container->get(ViewController::class);
        }

        return $controller;
    }
}
