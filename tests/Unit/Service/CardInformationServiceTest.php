<?php

namespace App\Tests\Unit\Service;

use App\DataProvider\JsonHttpDataProvider;
use App\Service\CardInformationService;
use PHPUnit\Framework\TestCase;

class CardInformationServiceTest extends TestCase
{
    private JsonHttpDataProvider $mockDataProvider;

    protected function setUp(): void
    {
        $this->mockDataProvider = $this->createMock(JsonHttpDataProvider::class);
        $this->cardInformationService = new CardInformationService($this->mockDataProvider);
    }

    public function testGetCardInformation(): void
    {
        $expectedCardNumber = '45717360';
        $expectedJsonResponse = json_decode('{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Visa\/Dankort","prepaid":false,"country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"\ud83c\udde9\ud83c\uddf0","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank","url":"www.jyskebank.dk","phone":"+4589893300","city":"Hj\u00f8rring"}}');
        // Arrange
        $this->mockDataProvider->expects($this->once())
            ->method('setInlineRequestParams')
            ->with([$expectedCardNumber]);
        $this->mockDataProvider->expects($this->once())
            ->method('setHeaders')
            ->with(["Content-Type" => "text/plain", "Accept" => "application/json"]);
        $this->mockDataProvider->expects($this->once())
            ->method('getJson')
            ->willReturn($expectedJsonResponse);

        // Act
        $result = $this->cardInformationService->getCardInformation($expectedCardNumber);

        // Assert
        $this->assertSame($expectedJsonResponse, $result);

    }
}
