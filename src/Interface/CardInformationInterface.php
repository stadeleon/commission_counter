<?php

namespace App\Interface;

use stdClass;

interface CardInformationInterface
{
    public function getCardInformation(string $cardNumber): stdClass;
}