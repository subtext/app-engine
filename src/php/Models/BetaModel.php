<?php

namespace Subtext\AppFactory\Models;

/**
 * Class BetaModel
 *
 * @package Subtext\AppFactory
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class BetaModel extends \Subtext\AppFactory\Base\Model
{

    public function getData(): array
    {
        return ['content' => 'zodiac.twig', 'image' => ['src' => '/images/pig.jpg', 'alt' => 'Pig']];
    }
}