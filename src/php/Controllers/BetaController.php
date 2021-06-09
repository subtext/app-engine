<?php

namespace Subtext\AppFactory\Controllers;

use Subtext\AppFactory\Base\Controller;
use Subtext\AppFactory\Models\BetaModel;
use Subtext\AppFactory\Views\BasicView;

/**
 * Class BetaController
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class BetaController extends Controller
{
    public function __construct(BetaModel $model, BasicView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }
}
