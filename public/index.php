<?php
namespace Subtext\AppFactory;

try {
    $root = \realpath('..');
    require_once($root . '/vendor/autoload.php');
    $bootstrap = new Bootstrap($root);
    $app = $bootstrap->getApplication();
    $app->execute();
} catch (\Throwable $e) {
    $error = new Fallback($e);
    $error->failGracefully();
}
