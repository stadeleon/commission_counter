<?php
require 'vendor/autoload.php';
require 'config/injectionLoader.php';

use App\Container\Container;
use App\Service\CommissionCounterService;

/** @var Container $container */
$commissionCounterService = $container->get(CommissionCounterService::class);

$file_handler = fopen($argv[1], 'r');
$commissionCounterService->iterate($file_handler);

fclose($file_handler);
