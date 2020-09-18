<?php

namespace Subtext\AppFactory\Base;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 *
 * @package Subtext\AppFactory\Base
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
abstract class Controller
{
    abstract public function execute(): Response;
}
