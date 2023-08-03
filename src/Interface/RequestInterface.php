<?php

namespace App\Interface;

interface RequestInterface
{
    public function __construct(string $baseUrl, string $requestType);
    public function get(): string;
}