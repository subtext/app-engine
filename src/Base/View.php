<?php

namespace Subtext\AppEngine\Base;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class View
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
abstract class View
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param array $data
     * @return Response
     */
    abstract public function display(array $data): Response;
}
