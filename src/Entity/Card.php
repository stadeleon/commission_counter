<?php

namespace App\Entity;

use stdClass;

class Card
{
    public string $countryCode;

    public function __construct(private readonly stdClass $cardData)
    {
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