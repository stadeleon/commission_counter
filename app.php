<?php
require 'vendor/autoload.php';


use App\Service\CommissionCounterService;

$commissionCounter = new CommissionCounterService();

$file_handler = fopen($argv[1], 'r');
$commissionCounter->iterate($file_handler);

fclose($file_handler);
