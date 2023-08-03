<?php

namespace App\Service;

use App\Interface\ExchangeRatesProviderInterface;
use App\Interface\RequestInterface;

class ExchangeRatesService implements ExchangeRatesProviderInterface
{
    public function __construct(private readonly RequestInterface $dataProvider, private readonly string $apiKey)
    {
    }

    public function getExchangeRate(string $baseCurrency, string $exchangeCurrency): float
    {
        $this->dataProvider->setGetRequestParams(['symbols' => $exchangeCurrency , 'base' => $baseCurrency]);
        $headers = [
            "Content-Type" => "text/plain",
            "Accept" => "application/json",
            "apikey" => $this->apiKey
        ];
        $this->dataProvider->setHeaders($headers);
        $response = $this->dataProvider->getJson();

        return floatval($response->rates->{$exchangeCurrency});
    }
}