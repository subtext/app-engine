<?php

namespace Subtext\AppFactory\Views;

use Subtext\AppFactory\Base\View;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class DefaultView
 *
 * @package Subtext\AppFactory\Views
 * @copyright Subtext Productions 2007-2019 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class DefaultView extends View
{
    /**
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display(): Response
    {
        $response = new Response($this->twig->render(
            'index.twig',
            ['content' => 'Hello, World!']
        ));

        return $response;
    }
}
