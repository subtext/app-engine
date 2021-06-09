<?php

namespace Subtext\AppFactory;

use DI\ContainerBuilder;
use Exception;
use InvalidArgumentException;
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
     * @var string The path to di configs and site routes
     */
    private $configPath;

    /**
     * Bootstrap constructor
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (!empty($path)) {
            $this->rootPath = rtrim($path, '/') . '/';
        } else {
            $this->rootPath = dirname(__DIR__, 2) . '/';
        }
        $this->configPath = $this->rootPath . 'config/';
    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            $configFile = getenv('APP_CONFIG') ? getenv('APP_CONFIG') : 'production.php';
            // @codeCoverageIgnoreStart
            if (!file_exists($this->configPath . $configFile)) {
                throw new InvalidArgumentException(
                    "The variable APP_CONFIG=$configFile must be set and valid; file not found"
                );
            }
            // @codeCoverageIgnoreEnd
            $builder = new ContainerBuilder();
            $builder->addDefinitions($this->configPath . $configFile);
            $this->container = $builder->build();
        }

        return $this->container;
    }

    /**
     * @return Application
     * @throws Exception
     */
    public function getApplication(): Application
    {
        if (!$this->application instanceof Application) {
            $this->application = $this->getContainer()->get(Application::class);
        }

        return $this->application;
    }
}
