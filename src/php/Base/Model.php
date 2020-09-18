<?php

namespace Subtext\AppFactory\Base;

use Aws\SecretsManager\SecretsManagerClient;

/**
 * Class Model
 *
 * @package Subtext\AppFactory\Base
 * @copyright Subtext Productions 2007-2020 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
class Model
{
    private $secrets;

    public function __construct()
    {
        $this->secrets = new SecretsManagerClient([]);
    }

}
