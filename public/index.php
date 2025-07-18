<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

namespace Subtext\AppEngine;

use Throwable;

try {
    $app  = null;
    $path = dirname(__DIR__);
    require_once($path . '/vendor/autoload.php');
    $bootstrap = new Bootstrap($path);
    $app = $bootstrap->application;
    $app->execute();
} catch (Throwable $e) {
    $error = new class ($e) extends Fallback {
        protected function getOutput(Throwable $error = null): string
        {
            return <<<EOF
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="utf-8" />
                    <title>App-Factory</title>
                </head>
                <body>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <h1>{$error->getMessage()}</h1>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
            EOF;
        }
    };
    $error->failGracefully();
    $app?->close();
}
