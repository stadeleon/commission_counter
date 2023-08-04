<?php

use App\Container\Container;

$config = require 'config.php';

$container = new Container($config['config']);

foreach ($config['services'] as $serviceName => $serviceCallback) {
    $container->register($serviceName, $serviceCallback);
}