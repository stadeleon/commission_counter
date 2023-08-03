<?php

namespace App\Factory;

use App\Entity\Transaction;
use Exception;
use stdClass;

class TransactionFactory
{
    public function createTransaction($row): Transaction
    {
        $transactionData = json_decode($row);

        if (!$transactionData && !is_a($transactionData, stdClass::class)) {
            throw new Exception('Transaction data processing error');
        }

        return new Transaction($transactionData);
    }
}