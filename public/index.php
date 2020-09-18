<?php
/**
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

namespace Subtext\AppFactory;

try {
    $path = dirname(__DIR__);
    require_once($path . '/vendor/autoload.php');
    $bootstrap = new Bootstrap($path);
    $app = $bootstrap->getApplication();
    $app->execute();
} catch (\Throwable $e) {
    $error = new Fallback($e);
    $error->failGracefully();
}
