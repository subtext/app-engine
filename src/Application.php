<?php

namespace Subtext\AppEngine;

use Psr\Log\LoggerInterface;
use RuntimeException;
use Subtext\AppEngine\Controllers\Loader;
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
     * @param Loader          $loader Dependency injected controller resolver
     * @param LoggerInterface $logger Logging for graceful failures
     */
    public function __construct(
        private Loader          $loader,
        private LoggerInterface $logger,
    ) {}

    /**
     * Executes the main application logic using pre-configured resources
     * provided by the dependency injection container. The container, built
     * using definitions from config/default.php, includes initialized services
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
            $this->loader->getController()->execute(
                $this->loader->getParams()
            )->send();
        } catch (Throwable $e) {
            $this->logger->debug($e->getMessage());
            $this->logger->debug($e->getTraceAsString());
            throw new RuntimeException("Oops... there was a problem", 404, $e);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function close(): void
    {
        $this->logger->debug("application closed");
    }
}
