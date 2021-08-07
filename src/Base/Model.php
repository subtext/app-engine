<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */

namespace Subtext\AppEngine\Base;

use Aws\SecretsManager\SecretsManagerClient;

/**
 * Class Model
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license MIT
 */
abstract class Model
{
    abstract public function getData(): array;
}
