<?php

namespace App\Tests\Entity;

use App\Entity\Transaction;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use stdClass;
use Faker\Factory;

class TransactionTest extends TestCase
{
    private stdClass $fakeTransactionDetails;
    private Transaction $transaction;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->fakeTransactionDetails = (object)[
            'bin' => $this->faker->numerify('############'),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => $this->faker->currencyCode,
        ];
        $this->transaction = new Transaction($this->fakeTransactionDetails);
    }

    public function testGetters()
    {
        $this->assertEquals($this->fakeTransactionDetails->bin, $this->transaction->bin);

        $this->assertEquals($this->fakeTransactionDetails->amount, $this->transaction->amount);

        $this->assertEquals($this->fakeTransactionDetails->currency, $this->transaction->currency);
    }
}
