<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

namespace Subtext\AppEngine\Base;

use Subtext\AppEngine\Services\Database;

/**
 * Class Model
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class Model
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    abstract public function getData(): array;
}
