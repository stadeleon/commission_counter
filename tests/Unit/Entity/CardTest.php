<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Card;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

class CardTest extends TestCase
{
    private stdClass $fakeCardData;
    private Card $card;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->fakeCardData = (object)['country' => (object)['alpha2' => $this->faker->countryCode]];
        $this->card = new Card($this->fakeCardData);
    }

    public function testGetCountryCode()
    {
        $countryCode = $this->card->getCountryCode();

        $this->assertEquals($this->fakeCardData->country->alpha2, $countryCode);
    }
}
