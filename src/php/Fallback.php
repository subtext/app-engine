<?php

namespace Subtext\AppFactory;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Fallback
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
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
        if (headers_sent()) {
            die($this->error->getMessage());
        } else {
            $html = "<div><h1>" . $this->error->getPrevious()->getMessage() . "</h1><pre>" . $this->error->getTraceAsString() . "</pre></div>";

            $response = new Response($html);
            $response->send();
            exit(16);
        }
    }
}
