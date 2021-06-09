<?php

namespace Subtext\AppFactory\Controllers;

use Subtext\AppFactory\Models\AlphaModel;
use Subtext\AppFactory\Views\BasicView;

/**
 * Class AlphaController
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class AlphaController extends BasicController
{
    public function __construct(BasicView $view, AlphaModel $model)
    {
        $this->model = $model;
        $this->view = $view;
    }
}
