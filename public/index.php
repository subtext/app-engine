<?php
/**
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

namespace Subtext\AppFactory;

try {
    require_once(__DIR__ . '/vendor/autoload.php');
    $bootstrap = new Bootstrap(__DIR__);
    $app = $bootstrap->getApplication();
    $app->execute();
} catch (\Throwable $e) {
    $error = new Fallback($e);
    $error->failGracefully();
}
