<?php

namespace Subtext\AppEngine;

use DI\ContainerBuilder;
use Exception;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Class Bootstrap
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
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
        $this->rootPath = $this->resolveProjectLocation($path);
        $this->configPath = "$this->rootPath/config";
    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            $configFile = getenv('APP_CONFIG') ? getenv('APP_CONFIG') : 'production.php';
            $configPath = "$this->configPath/$configFile";
            $builder = new ContainerBuilder();
            $builder->addDefinitions($configPath);
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

    /**
     * @param string $path
     * @return string
     * @throws InvalidArgumentException
     */
    private function resolveProjectLocation(string $path): string
    {
        if (empty($path) || !is_dir($path)) {
            throw new InvalidArgumentException(
                'The path provided to Bootstrap is not a valid directory'
            );
        }
        $directory = rtrim($path, '/');
        if (!is_dir($directory . '/config')) {
            throw new InvalidArgumentException(
                'The path provided to Bootstrap does not contain a valid config directory'
            );
        }

        return $directory;
    }
}
