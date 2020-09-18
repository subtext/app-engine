<?php

namespace Subtext\AppFactory\Controllers;

use Subtext\AppFactory\Base\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BasicController
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class BasicController extends Controller
{
    public function execute(): Response
    {
        $data = $this->model->getData();
        return $this->view->display($data);
    }
}