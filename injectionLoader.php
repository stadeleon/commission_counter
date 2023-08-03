<?php
use App\Service\Container;

$config = require 'config.php';

$container = new Container();

foreach ($config['services'] as $serviceName => $serviceCallback) {
    $container->register($serviceName, $serviceCallback);
}