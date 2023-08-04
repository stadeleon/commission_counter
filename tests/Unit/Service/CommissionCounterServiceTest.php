<?php

namespace App\Tests\Unit\Service;

use App\Entity\Card;
use App\Entity\Transaction;
use App\Factory\CardFactory;
use App\Factory\TransactionFactory;
use App\Service\CardInformationService;
use App\Service\CardValidationService;
use App\Service\CommissionCounterService;
use App\Service\ExchangeRatesService;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class CommissionCounterServiceTest extends TestCase
{
    private CommissionCounterService $commissionCounterService;
    private TransactionFactory $transactionFactory;
    private CardInformationService $cardInformationService;
    private CardFactory $cardFactory;
    private CardValidationService $cardValidationService;
    private ExchangeRatesService $exchangeRatesService;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        $this->transactionDetails = (object) [
            'bin' => $this->faker->numerify('############'),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => $this->faker->currencyCode
        ];

        $this->transactionFactory = $this->createMock(TransactionFactory::class);
        $transaction = new Transaction($this->transactionDetails);
        $this->transactionFactory
            ->method('createTransaction')
            ->willReturn($transaction);

        $this->cardInformationService = $this->getMockBuilder(CardInformationService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeCountryCode = $this->faker->countryCode;

        $this->cardInformationService
            ->method('getCardInformation')
            ->willReturn((object)['country' => (object)['alpha2' => $this->fakeCountryCode]]);

        $mockCardData = (object)['country' => (object)['alpha2' => $this->fakeCountryCode]];
        $this->mockCard = new Card($mockCardData);

        $this->cardFactory = $this->getMockBuilder(CardFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardFactory
            ->method('createCard')
            ->willReturn($this->mockCard);

        $this->cardValidationService = new CardValidationService();
        $this->exchangeRatesService = $this->getMockBuilder(ExchangeRatesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeRate = $this->faker->randomFloat(4, 1, 2);
        $this->exchangeRatesService->method('getExchangeRate')->willReturn($this->fakeRate);

        $this->commissionCounterService = new CommissionCounterService(
            $this->transactionFactory,
            $this->cardInformationService,
            $this->cardFactory,
            $this->cardValidationService,
            $this->exchangeRatesService
        );
    }

    public function testCountCommission(): void
    {
        $expectedTransactionAmount = $this->transactionDetails->amount;
        $expectedExchangeRate = $this->fakeRate;
        $expectedIsEuCard = $this->cardValidationService->isEuropeIssuedCard($this->fakeCountryCode);

        $reflectionMethod = new \ReflectionMethod(CommissionCounterService::class, 'countCommission');
        $reflectionMethod->setAccessible(true);

        $expectedCommission = self::countCommission($expectedTransactionAmount, $expectedExchangeRate, $expectedIsEuCard);

        $actualCommission = $reflectionMethod->invoke($this->commissionCounterService, $expectedTransactionAmount, $expectedExchangeRate, $expectedIsEuCard);

        $this->assertEquals($expectedCommission, $actualCommission, 'Commission calculation is incorrect.');
    }

    private static function countCommission(float $amount, float $exchangeRate, bool $isEuCard): float
    {
        $eurAmount = $amount * $exchangeRate;
        $rate = $isEuCard ? 0.01 : 0.02;
        $commission = round(($eurAmount * $rate), 2, PHP_ROUND_HALF_UP);
        return number_format($commission, 2, '.', '');
    }
}
