<?php

namespace Subtext\AppEngine;

use DI\ContainerBuilder;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Subtext\AppEngine\Exceptions\ConfigNotFoundException;

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
     * @var ContainerInterface | null
     */
    public ?ContainerInterface $container = null {
        get {
            if (!$this->container instanceof ContainerInterface) {
                if (!file_exists($this->configFile)) {
                    throw new ConfigNotFoundException(
                        sprintf("Config file: '%s' not found.", $this->configFile)
                    );
                }
                $builder = new ContainerBuilder();
                $builder->addDefinitions($this->configFile);
                $this->container = $builder->build();
            }

            return $this->container;
        }
    }

    /**
     * @var Application | null
     */
    public ?Application $application = null {
        get {
            if (!$this->application instanceof Application) {
                $this->application = $this->container?->get(Application::class);
            }

            return $this->application;
        }
    }

    /**
     * @var string The root path to the project
     */
    private string $rootPath;

    /**
     * @var string The path to di configuration settings file
     */
    private string $configFile;

    /**
     * Bootstrap constructor
     *
     * @param string $path    Path to app root directory
     * @param string $config  Path to di configuration file
     */
    public function __construct(string $path, string $config = 'default.php')
    {
        $this->rootPath   = $this->resolveProjectLocation($path);
        $this->configFile = "$this->rootPath/config/$config";
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
