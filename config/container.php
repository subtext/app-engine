<?php

use function DI\factory;

return [
    \Psr\Container\ContainerInterface::class => DI\factory(
        function(\Psr\Container\ContainerInterface $c) {
            return $c;
        }
    )
];
