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
abstract class Fallback
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

        $response = new Response(
            $this->getOutput($this->error),
            $this->error->getCode()
        );
        $response->send();
    }

    abstract protected function getOutput(Throwable $error): string;
}
