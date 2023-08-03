<?php

namespace App\Service;

use App\Entity\Card;
use App\Factory\TransactionFactory;
use App\Interface\CardInformationProviderInterface;
use App\Interface\CardValidatorInterface;
use App\Interface\ExchangeRatesProviderInterface;
use Exception;
use Generator;

class CommissionCounterService
{
    public function __construct(
        private readonly TransactionFactory               $transactionFactory,
        private readonly CardInformationProviderInterface $cardInformationService,
        private readonly CardValidatorInterface           $cardValidatorService,
        private readonly ExchangeRatesProviderInterface   $ratesApiService,
    )
    {
    }

    private function iterator($file_handle): Generator
    {
        while (!feof($file_handle)) {
            yield fgets($file_handle);
        }
    }

    public function iterate($source): void
    {
        foreach ($this->iterator($source) as $row) {
            try {
                if (!$row) {
                    continue;
                }
                $transaction = $this->transactionFactory->createTransaction($row);
                $cardInfo = $this->cardInformationService->getCardInformation($transaction->bin);
                $card = new Card($cardInfo);
                $this->cardValidatorService->setCard($card);
                $isEuCard = $this->cardValidatorService->isEuropeIssuedCard();
                $exchangeRate = $this->ratesApiService->getExchangeRate($transaction->currency, 'EUR');
                $commission = $this->countCommission($transaction->amount, $exchangeRate, $isEuCard);

                print "$commission \n";
            } catch (Exception $e) {
                print $e->getMessage();
            }
        }
    }

    private function countCommission(float $amount, float $exchangeRate, bool $isEuCard): float
    {
        $eurAmount = $amount * $exchangeRate;

        return $eurAmount * ($isEuCard ? 0.01 : 0.02);
    }
}