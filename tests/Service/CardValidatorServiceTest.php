<?php

namespace App\Tests\Service;

use App\Service\CardValidationService;
use PHPUnit\Framework\TestCase;

class CardValidatorServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->cardValidationService = new CardValidationService();
        $this->euCountries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
            'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];
        $this->nonEuCountries = ['US', 'CA', 'JP'];
    }

    public function testIsEuropeIssuedCard(): void
    {
        foreach ($this->euCountries as $country) {
            $this->assertTrue($this->cardValidationService->isEuropeIssuedCard($country));
        }

        foreach ($this->nonEuCountries as $country) {
            $this->assertFalse($this->cardValidationService->isEuropeIssuedCard($country));
        }
    }
}
