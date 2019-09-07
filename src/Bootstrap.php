<?php
namespace Subtext\AppFactory;

use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

/**
 * Class Bootstrap
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
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
        (Dotenv::create($this->rootPath))->load();
    }

    /**
     * @return ContainerInterface
     * @throws \Exception
     */
    public function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            $builder = new ContainerBuilder();
            $configFile = '';
            switch ($_SERVER['APP_ENV']) {
                case 'PROD':
                    $configFile = '/config/container.php';
                    break;
                case 'DEV':
                    $configFile = '/config/developer.php';
                    break;
                case 'BUILD':
                    $configFile = '/config/build.php';
                    break;
                case 'TEST':
                    $configFile = '/config/unit.php';
                    break;

            }
            printf(STDERR, $configFile);
            die();
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
            $container = $this->getContainer();
            $this->application = $container->get(Application::class);
        }

        return $this->application;
    }
}
