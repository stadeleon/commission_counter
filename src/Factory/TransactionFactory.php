<?php

namespace App\Factory;

use App\Entity\Transaction;
use App\Interface\TransactionFactoryInterface;
use Exception;
use stdClass;

class TransactionFactory implements TransactionFactoryInterface
{
    public function createTransaction(string $row): Transaction
    {
        $transactionData = json_decode($row);

        if (!$transactionData && !is_a($transactionData, stdClass::class)) {
            throw new Exception('Transaction data processing error');
        }

        return new Transaction($transactionData);
    }
}