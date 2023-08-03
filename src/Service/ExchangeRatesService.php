<?php

namespace App\Service;

use App\DataProvider\AbstractHttpDataProvider;
use App\DataProvider\JsonHttpDataProvider;
use App\Interface\ExchangeRatesProviderInterface;
use App\Interface\RequestInterface;

class ExchangeRatesService implements ExchangeRatesProviderInterface
{
    private const BASE_URL = 'https://api.apilayer.com/exchangerates_data/latest';
    private RequestInterface $dataProvider;
    public function __construct()
    {
        $this->dataProvider = new JsonHttpDataProvider(self::BASE_URL, AbstractHttpDataProvider::GET);
    }

    public function getExchangeRate(string $baseCurrency, string $exchangeCurrency): float
    {
        $this->dataProvider->setGetRequestParams(['symbols' => $exchangeCurrency , 'base' => $baseCurrency]);
        $headers = [
            "Content-Type" => "text/plain",
            "Accept" => "application/json",
            "apikey" => "U8NVZ5jGsmKK6fx1P5OQUSX6gaWD2QZJ"
        ];
        $this->dataProvider->setHeaders($headers);
        $response = $this->dataProvider->getJson();

        return floatval($response->rates->{$exchangeCurrency});
    }
}