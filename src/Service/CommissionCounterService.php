<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Transaction;
use Generator;
use stdClass;

class CommissionCounterService
{
    private function iterator($file_handle): Generator
    {
        while (!feof($file_handle)) {
            yield fgets($file_handle);
        }
    }

    public function iterate($source): void
    {
        foreach ($this->iterator($source) as $row) {
            $transactionData = json_decode($row);

            if (!$transactionData && !is_a($transactionData, stdClass::class)) {
                continue;
            }

            $transaction = new Transaction(json_decode($row));
            $commission = $this->countCommission($transaction);
            print "$commission \n";
        }
    }

    private function countCommission(Transaction $transaction): float
    {
        $card = new Card((new CardInformationService())->getCardInformation($transaction->bin));
        $isEuCard = (new CardValidatorService($card))->isEuropeIssuedCard();
        $exchangeRate = (new RatesApiService())->getExchangeRate($transaction->currency, 'EUR');
        $eurAmount = $transaction->amount * $exchangeRate;

        return $eurAmount * ($isEuCard ? 0.01 : 0.02);
    }
}