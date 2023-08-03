<?php

namespace App\Interface;

use App\Entity\Transaction;

interface TransactionFactoryInterface
{
    public function createTransaction(string $row): Transaction;
}