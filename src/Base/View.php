<?php
namespace Subtext\AppFactory\Base;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class View
 *
 * @package Subtext\AppFactory\Base
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
abstract class View
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    abstract public function display(): Response;
}