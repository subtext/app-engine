<?php
/**
 * @package Subtext\AppEngine
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */

namespace Subtext\AppEngine\Base;

use Aws\SecretsManager\SecretsManagerClient;

/**
 * Class Model
 *
 * @package Subtext\AppEngine\Base
 * @copyright Subtext Productions 2007-2021 All rights reserved
 * @license GPL-3.0-only or GPL-3.0-or-later
 */
abstract class Model
{
    abstract public function getData(): array;
}
