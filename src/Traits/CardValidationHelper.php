<?php

namespace App\Traits;

trait CardValidationHelper
{
    private static array $euCountriesList = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
        'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    public function isEuropeIssuedCard(string $countryCode): bool
    {
        return in_array($countryCode, self::$euCountriesList);
    }
}