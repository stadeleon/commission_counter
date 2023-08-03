<?php

namespace App\Interface;

use stdClass;

interface CardInformationProviderInterface
{
    public function getCardInformation(string $cardNumber): stdClass;
}