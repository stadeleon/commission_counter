<?php

use App\DataProvider\AbstractHttpDataProvider;
use App\DataProvider\JsonHttpDataProvider;
use App\Factory\TransactionFactory;
use App\Service\CardInformationService;
use App\Service\CardValidatorService;
use App\Service\CommissionCounterService;
use App\Service\ExchangeRatesService;

return [
    'config' => [
        'card_information_base_url' => 'https://lookup.binlist.net/',
        'exchange_rates_base_url' => 'https://api.apilayer.com/exchangerates_data/latest',
        'exchange_rates_api_key' => 'U8NVZ5jGsmKK6fx1P5OQUSX6gaWD2QZJ'
    ],
    'services' => [
        CommissionCounterService::class => function ($container) {
            return new CommissionCounterService(
                $container->get(TransactionFactory::class),
                $container->get(CardInformationService::class),
                $container->get(CardValidatorService::class),
                $container->get(ExchangeRatesService::class)
            );
        },
        JsonHttpDataProvider::class => function ($container) {
            $baseUrl = $container->getConfig()['card_information_base_url'];
            return new JsonHttpDataProvider($baseUrl, AbstractHttpDataProvider::GET);
        },
        CardInformationService::class => function ($container) {
            $baseUrl = $container->getConfig()['card_information_base_url'];
            return new CardInformationService(new JsonHttpDataProvider($baseUrl, AbstractHttpDataProvider::GET));
        },
        TransactionFactory::class => function ($container) {
            return new TransactionFactory();
        },
        CardValidatorService::class => function ($container) {
            return new CardValidatorService();
        },
        ExchangeRatesService::class => function ($container) {
            $base_url = $container->getConfig()['exchange_rates_base_url'];
            $api_key = $container->getConfig()['exchange_rates_api_key'];
            return new ExchangeRatesService(new JsonHttpDataProvider($base_url, AbstractHttpDataProvider::GET), $api_key);
        }
    ],
];