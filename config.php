<?php

use App\DataProvider\AbstractHttpDataProvider;
use App\DataProvider\JsonHttpDataProvider;
use App\Factory\TransactionFactory;
use App\Service\CardInformationProviderService;
use App\Service\CardValidatorService;
use App\Service\CommissionCounterService;
use App\Service\ExchangeRatesService;

return [
    'services' => [
        CommissionCounterService::class => function ($container) {
            return new CommissionCounterService(
                new TransactionFactory(),
                new CardInformationProviderService(),
                new CardValidatorService(),
                new ExchangeRatesService()
            );
        },
        JsonHttpDataProvider::class => function ($container) {
            return new JsonHttpDataProvider(self::BASE_URL, AbstractHttpDataProvider::GET);
        },
        CardInformationProviderService::class => function ($container) {
            return new CardInformationProviderService($container->get(JsonHttpDataProvider::class));
        },
        TransactionFactory::class => function ($container) {
            return new TransactionFactory();
        },
        CardValidatorService::class => function ($container) {
            return new CardValidatorService();
        },
        ExchangeRatesService::class => function ($container) {
            return new ExchangeRatesService();
        }
    ],
];