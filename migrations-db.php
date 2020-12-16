<?php

return [
    'dbname'   => getenv('APP_DB_NAME'),
    'user'     => getenv('APP_DB_USER'),
    'password' => getenv('APP_DB_PASS'),
    'host'     => getenv('APP_DB_HOST'),
    'driver'   => 'pdo_mysql',
];
