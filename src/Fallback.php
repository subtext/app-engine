<?php

namespace Subtext\AppEngine;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Fallback
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class Fallback
{
    /**
     * @var Throwable
     */
    private $error;

    /**
     * Fallback constructor
     *
     * @param Throwable $e
     */
    public function __construct(Throwable $e)
    {
        $this->error = $e;
    }

    /**
     * Res ipsa loquitur
     */
    public function failGracefully(): void
    {
        $errorPage = <<<EOF
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="/css/style.css" />
        <title>App-Factory</title>
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-expand-sm navbar-light bg-light">
                <a class="navbar-brand" href="/">Subtext</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navHidden" aria-controls="navHidden" aria-expanded="false" aria-label="Toggle Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navHidden">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/alpha">Alpha</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/beta">Beta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gamma">Gamma</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/delta">Delta</a>
                        </li>

                    </ul>
                </div>
            </nav>
            <div class="row">
                <div class="col">
                    <h1 class="display-1 text-center mt-5">{$this->error->getMessage()}</h1>
                </div>
            </div>
        </div>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/index.min.js"></script>
    </body>
</html>
EOF;
        $response = new Response($errorPage, $this->error->getCode());
        $response->send();
    }
}
