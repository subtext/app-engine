<?php

namespace Subtext\AppFactory\Controllers;

use Subtext\AppFactory\Base\Controller;
use Subtext\AppFactory\Models\DefaultModel;
use Subtext\AppFactory\Views\DefaultView;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewController
 *
 * @package Subtext\AppFactory\Controllers
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class ViewController extends Controller
{
    private $model;

    private $view;

    public function __construct(DefaultModel $model, DefaultView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function execute(): Response
    {
        return $this->view->display();
    }
}
