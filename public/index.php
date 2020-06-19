<?php
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
