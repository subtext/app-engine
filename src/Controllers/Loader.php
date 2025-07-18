<?php

namespace Subtext\AppEngine\Controllers;

use Subtext\AppEngine\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class Loader
{
    private function __construct(private Controller $controller, private mixed $params)
    {}

    public static function resolveController(Router $router, Request $request): array
    {
        return $router->matchRequest($request);
    }

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
