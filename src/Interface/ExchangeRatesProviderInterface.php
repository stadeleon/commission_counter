<?php

namespace App\Interface;

interface ExchangeRatesProviderInterface
{
    public function getExchangeRate(string $baseCurrency, string $exchangeCurrency): float;
}