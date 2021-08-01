<?php

namespace Subtext\AppEngine\Base;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
abstract class Controller
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var View
     */
    protected $view;

    /**
     * Create a method which will handle all the necessary tasks for this controller
     *
     * @return Response
     */
    public function execute(): Response
    {
        $data = $this->model->getData();
        return $this->view->display($data);
    }
}
