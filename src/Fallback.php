<?php

namespace Subtext\AppFactory;

use Throwable;

/**
 * Class Fallback
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2019 All rights reserved
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
        echo $this->error->getMessage();
    }
}
