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
     * Create a method which will handle all the necessary tasks for this controller
     *
     * @return Response
     */
    abstract function execute(array $params): Response;
}
