<?php

namespace Subtext\AppFactory\Controllers;

use Subtext\AppFactory\Models\HomeModel;
use Subtext\AppFactory\Views\BasicView;

/**
 * Class HomeController
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class HomeController extends BasicController
{
    public function __construct(HomeModel $model, BasicView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }
}
