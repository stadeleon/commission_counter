<?php

namespace App\Tests\Unit\Factory;

use App\Entity\Transaction;
use App\Factory\TransactionFactory;
use Exception;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class TransactionFactoryTest extends TestCase
{
    private TransactionFactory $transactionFactory;
    private Generator$faker;

    protected function setUp(): void
    {
        $this->transactionFactory = new TransactionFactory();
        $this->faker = Factory::create();
    }

    public function testCreateTransaction()
    {
        $fakeTransactionDetails = (object)[
            'bin' => $this->faker->numerify('############'),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => $this->faker->currencyCode
        ];

        $fakeRow = json_encode($fakeTransactionDetails);

        $transaction = $this->transactionFactory->createTransaction($fakeRow);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($fakeTransactionDetails->bin, $transaction->bin);
        $this->assertEquals($fakeTransactionDetails->amount, $transaction->amount);
        $this->assertEquals($fakeTransactionDetails->currency, $transaction->currency);
    }

    public function testCreateTransactionWithInvalidData()
    {
        $this->expectException(Exception::class);

        $invalidFakeRow = 'invalid_json_data';

        $this->transactionFactory->createTransaction($invalidFakeRow);
    }
}
