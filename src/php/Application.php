<?php

namespace Subtext\AppFactory;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use \Throwable;

/**
 * Class Application
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
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
     * @var Router
     */
    private $router;

    /**
     * Application constructor
     *
     * @param ContainerInterface $container
     * @param Request            $request
     * @param Router             $router
     */
    public function __construct(ContainerInterface $container, Request $request, Router $router)
    {
        $this->container = $container;
        $this->request = $request;
        $this->router = $router;
    }

    public function execute(): void
    {
        try {
            if (mb_substr($this->request->getUri(), -1) === '/') {
                $uri = rtrim($this->request->getRequestUri(), "/");
                $params = $this->router->match($uri);
            } else {
                $params = $this->router->matchRequest($this->request);
            }
            if (!$this->container->has($params['_controller'])) {
                throw new ResourceNotFoundException("Controller does not exist");
            }
            $controller = $this->container->get($params['_controller']);
            $response = $controller->execute();
            $response->send();
        } catch (Throwable $e) {
            throw new RuntimeException("Oops... there was a problem", 404, $e);
        }
    }

    public function close(): void
    {
        // send any errors to bugsnag
    }
}
