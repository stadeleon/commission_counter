<?php

namespace App\Entity;

use stdClass;

class Transaction
{
    public readonly int $bin;
    public readonly float $amount;
    public readonly string $currency;

    public function __construct(stdClass $transactionDetails)  //update currency to ENUM
    {
        $this->currency = $transactionDetails->currency;
        $this->amount = $transactionDetails->amount;
        $this->bin = $transactionDetails->bin;
    }
}