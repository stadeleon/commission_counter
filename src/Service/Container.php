<?php

namespace App\Service;

use Exception;

class Container
{
    private array $services = [];

    public function register(string $serviceName, callable $callback): void
    {
        $this->services[$serviceName] = $callback;
    }

    public function get(string $serviceName)
    {
        if (!isset($this->services[$serviceName])) {
            throw new Exception("Service {$serviceName} not registered");
        }

        $serviceCallback = $this->services[$serviceName];

        return $serviceCallback($this);
    }

}