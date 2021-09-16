<?php

namespace Subtext\AppEngine;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Fallback
 *
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
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
