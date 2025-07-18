<?php

namespace Subtext\AppEngine\Controllers;

use Subtext\AppEngine\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class Loader
{
    /**
     * Can only be instantiated using the public static create method
     *
     * @param Controller $controller
     * @param mixed $params
     */
    private function __construct(private Controller $controller, private mixed $params)
    {}

    /**
     * @param Router $router   The symfony router component
     * @param Request $request The symfony request component
     *
     * @return array The route parameters
     */
    public static function resolveController(Router $router, Request $request): array
    {
        return $router->matchRequest($request);
    }

    /**
     * @param Controller $controller The controller to be passed to the app
     * @param mixed $params          The route params as an array
     *
     * @return self
     */
    public static function create(Controller $controller, mixed $params): self
    {
        return new self($controller, $params);
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function getParams(): mixed
    {
        return $this->params;
    }
}
