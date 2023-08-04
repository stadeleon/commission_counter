<?php

namespace App\Tests\Unit\Factory;

use App\Entity\Card;
use App\Factory\CardFactory;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class CardFactoryTest extends TestCase
{
    private CardFactory $cardFactory;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->cardFactory = new CardFactory();
        $this->faker = Factory::create();
    }

    public function testCreateCard()
    {
        $fakeCardInfo = (object)[
            'country' => (object)[
                'alpha2' => $this->faker->countryCode
            ]
        ];

        $card = $this->cardFactory->createCard($fakeCardInfo);

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals($fakeCardInfo->country->alpha2, $card->getCountryCode());
    }
}
