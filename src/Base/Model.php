<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

namespace Subtext\AppEngine\Base;

use Subtext\AppEngine\Services\Database;

/**
 * Model
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2025 All rights reserved
 * @license MIT
 */
class Model
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
