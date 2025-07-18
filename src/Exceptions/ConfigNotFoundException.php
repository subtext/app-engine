<?php

namespace Subtext\AppEngine\Exceptions;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;

class ConfigNotFoundException extends InvalidArgumentException implements ContainerExceptionInterface
{
}
