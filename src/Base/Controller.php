<?php

namespace Subtext\AppEngine\Base;

use Symfony\Component\HttpFoundation\Response;

/**
 * Controller
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class Controller
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var View
     */
    protected View $view;

    public function __construct(Model $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

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
