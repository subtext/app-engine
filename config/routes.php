<?php
/**
* @package Subtext\AppFactory
* @copyright Subtext Productions 2007-2020 All rights reserved
* @license GPL-3.0-only or GPL-3.0-or-later
*/

namespace Subtext\AppFactory;

use Subtext\AppFactory\Controllers;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('root', '/')->controller(Controllers\HomeController::class);
    $routes->add('alpha', '/alpha')->controller(Controllers\AlphaController::class);
    $routes->add('beta', '/beta')->controller(Controllers\BetaController::class);
    $routes->add('gamma', '/gamma')->controller(Controllers\GammaController::class);
    $routes->add('delta', '/delta')->controller(Controllers\DeltaController::class);
};
