<?php

namespace App\Tests\Integration;

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


/**
 * @group integration
 */
class IntegrationCommissionCounterServiceTest extends TestCase
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

        $this->transactionFactory = $this->createMock(TransactionFactory::class);
        $this->cardInformationService = $this->getMockBuilder(CardInformationService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->cardFactory = new CardFactory();
        $this->cardValidationService = new CardValidationService();
        $this->exchangeRatesService = $this->getMockBuilder(ExchangeRatesService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public static function dataProviderForTestIterate(): array
    {
        $faker = Factory::create();

        $euCountriesList = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
            'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $amount = $faker->randomFloat(2, 0, 1000);
            $input = json_encode((object)[
                'bin' => $faker->numerify('########'),
                'amount' => $amount,
                'currency' => $faker->currencyCode,
            ]);

            $fakeCountryCode = $faker->countryCode;
            $isEuCard = in_array($fakeCountryCode, $euCountriesList);
            $fakeExchangeRate = $faker->randomFloat(4, 0.5, 2);
            $expectedCommission = self::countCommission($amount, $fakeExchangeRate, $isEuCard);

            $data[] = [$input, $isEuCard, $fakeCountryCode, $fakeExchangeRate, $expectedCommission];
        }

        return $data;
    }

    /**
     * @dataProvider dataProviderForTestIterate
     */
    public function testIterate($input, $isEuCard, $fakeCountryCode, $fakeExchangeRate, $expectedCommission)
    {
        $this->cardInformationService
            ->expects($this->any())
            ->method('getCardInformation')
            ->willReturn((object)['country' => (object)['alpha2' => $fakeCountryCode]]);

        $this->exchangeRatesService
            ->expects($this->any())
            ->method('getExchangeRate')->willReturn($fakeExchangeRate);

        $transaction = new Transaction(json_decode($input));
        $this->transactionFactory
            ->method('createTransaction')
            ->willReturn($transaction);

        $this->commissionCounterService = new CommissionCounterService(
            $this->transactionFactory,
            $this->cardInformationService,
            $this->cardFactory,
            $this->cardValidationService,
            $this->exchangeRatesService
        );

        $inputStream = fopen('php://memory', 'r+');
        fwrite($inputStream, $input);
        rewind($inputStream);

        ob_start();
        $this->commissionCounterService->iterate($inputStream);
        $output = ob_get_clean();

        $outputLine = floatval(trim($output));

        $this->assertSame($expectedCommission, $outputLine);
    }

    private static function countCommission(float $amount, float $exchangeRate, bool $isEuCard): float
    {
        $eurAmount = $amount * $exchangeRate;
        $rate = $isEuCard ? 0.01 : 0.02;
        $commission = round(($eurAmount * $rate), 2, PHP_ROUND_HALF_UP);
        return number_format($commission, 2, '.', '');
    }
}
