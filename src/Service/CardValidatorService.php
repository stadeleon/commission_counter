<?php

namespace App\Service;

use App\Entity\Card;
use App\Interface\CardValidatorInterface;

class CardValidatorService implements CardValidatorInterface
{
    private Card $card;
    private static array $euCountriesList = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU',
        'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    public function __construct()
    {
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function isEuropeIssuedCard(): bool
    {
        return in_array($this->card->getCountryCode(), self::$euCountriesList);
    }
}