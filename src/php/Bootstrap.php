<?php

namespace Subtext\AppFactory;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

/**
 * Class Bootstrap
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class Bootstrap
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var string The root path to the project
     */
    private $rootPath;

    /**
     * Bootstrap constructor
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->rootPath = $path;
    }

    /**
     * @return ContainerInterface
     * @throws \Exception
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            $builder = new ContainerBuilder();
            $configFile = getenv('APP_CONFIG');
            $builder->addDefinitions($this->rootPath . $configFile);
            $this->container = $builder->build();
        }

        return $this->container;
    }

    /**
     * @return Application
     * @throws \Exception
     */
    public function getApplication(): Application
    {
        if (!$this->application instanceof Application) {
            $this->application = $this->getContainer()->get(Application::class);
        }

        return $this->application;
    }
}
