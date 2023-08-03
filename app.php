<?php
require 'vendor/autoload.php';
require 'config/injectionLoader.php';

use App\Service\CommissionCounterService;
use App\Service\Container;

/** @var Container $container */
$commissionCounterService = $container->get(CommissionCounterService::class);

$file_handler = fopen($argv[1], 'r');
$commissionCounterService->iterate($file_handler);

fclose($file_handler);
