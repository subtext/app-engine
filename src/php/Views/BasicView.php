<?php

namespace Subtext\AppFactory\Views;

use Subtext\AppFactory\Base\View;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class BasicView
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class BasicView extends View
{
    /**
     * @param array $data
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(array $data): Response
    {
        return new Response($this->twig->render('index.twig', $data));
    }
}