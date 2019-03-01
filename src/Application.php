<?php
namespace Subtext\AppFactory;

use Psr\Container\ContainerInterface;

/**
 * Class Application
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute(): void
    {
        echo "Hello, World";
    }
}
