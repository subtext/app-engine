<?php

namespace Subtext\AppFactory\Models;

use Subtext\AppFactory\Base\Model;

/**
 * Class HomeModel
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class HomeModel extends Model
{
    /**
     * @return string[]
     */
    public function getData(): array
    {
        return ['content' => 'home.twig', 'message' => 'Hello, World'];
    }
}