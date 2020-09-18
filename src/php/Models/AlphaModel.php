<?php

namespace Subtext\AppFactory\Models;

use Subtext\AppFactory\Base\Model;

/**
 * Class DefaultModel
 *
 * @package Subtext\AppFactory\Models
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class AlphaModel extends Model
{
    public function getData(): array
    {
        return ['content' => 'zodiac.twig', 'image' => ['src' => '/images/horse.jpg', 'alt' => 'Horse']];
    }
}
