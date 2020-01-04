<?php
namespace Subtext\AppFactory;

use Dotenv\Dotenv;

$root = dirname(__DIR__, 2);
require_once($root . '/vendor/autoload.php');
(Dotenv::create($root))->load();
