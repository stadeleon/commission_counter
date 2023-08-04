<?php

namespace App\Tests\Service;

use App\DataProvider\JsonHttpDataProvider;
use App\Service\ExchangeRatesService;
use PHPUnit\Framework\TestCase;
use Faker\Factory;
use Faker;

class ExchangeRatesServiceTest extends TestCase
{
    private ExchangeRatesService $exchangeRatesService;
    private JsonHttpDataProvider $mockDataProvider;
    private string $apiKey;
    private Faker\Generator $faker;
    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->apiKey = $this->faker->md5;
        $this->mockDataProvider = $this->createMock(JsonHttpDataProvider::class);
        $this->exchangeRatesService = new ExchangeRatesService($this->mockDataProvider, $this->apiKey);
    }

    public function testGetExchangeRate(): void
    {
        $baseCurrency = 'USD';
        $exchangeCurrency = 'EUR';
        $fakeRate = $this->faker->randomFloat(4, 1, 2);

        $response = (object) ['rates' => (object)[
            $exchangeCurrency => $fakeRate
        ]];

        $this->mockDataProvider->expects($this->once())
            ->method('setGetRequestParams')
            ->with(['symbols' => $exchangeCurrency, 'base' => $baseCurrency]);

        $this->mockDataProvider->expects($this->once())
            ->method('setHeaders')
            ->with($this->callback(function ($headers) {
                return isset($headers['apikey']) && $headers['apikey'] === $this->apiKey;
            }));

        $this->mockDataProvider->expects($this->once())
            ->method('getJson')
            ->willReturn($response);

        $expectedRate = floatval($fakeRate);
        $rate = $this->exchangeRatesService->getExchangeRate($baseCurrency, $exchangeCurrency);

        $this->assertEquals($expectedRate, $rate);
    }
}
