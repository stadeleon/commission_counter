<?php

namespace App\Entity;

use stdClass;

class Card
{
    public string $countryCode;
    private stdClass $cardData;

    public function __construct(stdClass $cardData)
    {
        $this->cardData = $cardData;
        $this->countryCode = $cardData->country->alpha2;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}