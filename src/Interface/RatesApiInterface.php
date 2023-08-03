<?php

namespace App\Interface;

interface RatesApiInterface
{
    public function getExchangeRate(string $baseCurrency, string $exchangeCurrency): float;
}