<?php

namespace Subtext\AppEngine\Base;

use Symfony\Component\HttpFoundation\Response;

/**
 * View
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class View
{
    /**
     * @param array $data
     * @return Response
     */
    abstract public function display(array $data): Response;
}
