<?php
namespace Subtext\AppEngine;

use Dotenv\Dotenv;

$root = dirname(__DIR__, 2);
require_once($root . '/vendor/autoload.php');
(Dotenv::create($root))->load();
