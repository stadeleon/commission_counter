<?php

namespace App\Tests\Service;

use App\DataProvider\JsonHttpDataProvider;
use App\Service\CardInformationService;
use PHPUnit\Framework\TestCase;

class CardInformationServiceTest extends TestCase
{
    private $mockDataProvider;

    protected function setUp(): void
    {
        $this->mockDataProvider = $this->createMock(JsonHttpDataProvider::class);
    }

    public function testGetCardInformation()
    {
        // Arrange
        $this->mockDataProvider->method('setInlineRequestParams')->willReturnSelf();
        $this->mockDataProvider->method('setHeaders')->willReturnSelf();
        $this->mockDataProvider->method('getJson')->willReturn((object) ['mock' => 'response']);

        // Act
        $cardInformationService = new CardInformationService();

    }
}
