<?php

namespace Subtext\AppEngine\Controllers;

use Closure;
use RuntimeException;
use Subtext\AppEngine\Controller;

class Factory
{
    public function __construct(private Closure $resolver)
    {}

    public function get(string $class): Controller
    {
        $controller = ($this->resolver)($class);
        if (!$controller instanceof Controller) {
            throw new RuntimeException(sprintf(
                'Controller %s could not be resolved',
                $class
            ));
        }
        return $controller;
    }
}
