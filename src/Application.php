<?php

namespace Subtext\AppEngine;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Router;
use Throwable;

/**
 * Class Application
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2025 All rights reserved
 * @license MIT
 */
final readonly class Application
{
    /**
     * @param ContainerInterface $container Dependency injection container
     * @param LoggerInterface    $logger    Logging for graceful failures
     * @param Request            $request   HttpFoundation request
     * @param Router             $router    HttpFoundation router
     */
    public function __construct(
        private ContainerInterface $container,
        private LoggerInterface    $logger,
        private Request            $request,
        private Router             $router
    ) {}

    /**
     * Executes the main application logic using pre-configured resources
     * provided by the dependency injection container. The container, built
     * using definitions from config/build.php, includes initialized services
     * and route mappings defined in config/routes.php. The execute() method
     * delegates control to the appropriate controller based on the resolved
     * route, coordinating the request lifecycle and invoking the correct
     * business logic through the controller layer.
     *
     * @return void
     */
    public function execute(): void
    {
        try {
            $params = $this->router->matchRequest($this->request);
            $name   = ($params['_controller'] ?? '');
            if (!$this->container->has($name)) {
                throw new ResourceNotFoundException(sprintf(
                    "Controller %s does not exist, or cannot be defined",
                    $name
                ));
            }
            if (($controller = $this->container->get($name)) instanceof Controller) {
                $controller->execute($params)->send();
            } else {
                throw new InvalidArgumentException(sprintf(
                    'Controller %s is not an instance of %s',
                    $controller::class,
                    Controller::class
                ));
            }
        } catch (Throwable $e) {
            $this->logger->debug($e->getMessage());
            throw new RuntimeException("Oops... there was a problem", 404, $e);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function close(): void
    {
        $this->logger->debug("Application closed");
    }
}
