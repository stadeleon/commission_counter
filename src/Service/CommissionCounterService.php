<?php

namespace App\Service;

use App\Interface\CardFactoryInterface;
use App\Interface\CardInformationProviderInterface;
use App\Interface\CardValidationInterface;
use App\Interface\ExchangeRatesProviderInterface;
use App\Interface\TransactionFactoryInterface;
use Exception;
use Generator;

class CommissionCounterService
{
    public function __construct(
        private readonly TransactionFactoryInterface      $transactionFactory,
        private readonly CardInformationProviderInterface $cardInformationService,
        private readonly CardFactoryInterface             $cardFactory,
        private readonly CardValidationInterface          $cardValidatorService,
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
                $card = $this->cardFactory->createCard($cardInfo);
                $isEuCard = $this->cardValidatorService->isEuropeIssuedCard($card->getCountryCode());
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
        $rate = $isEuCard ? 0.01 : 0.02;
        $commission = round(($eurAmount * $rate), 2, PHP_ROUND_HALF_UP);
        return number_format($commission, 2, '.', '');
    }
}