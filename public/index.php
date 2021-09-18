<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

namespace Subtext\AppEngine;

use Throwable;

try {
    $path = dirname(__DIR__);
    require_once($path . '/vendor/autoload.php');
    $bootstrap = new Bootstrap($path);
    $app = $bootstrap->getApplication();
    $app->execute();
} catch (Throwable $e) {
    $error = new Fallback($e);
    $error->failGracefully();
    $app->close();
}
