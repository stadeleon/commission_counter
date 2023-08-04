<?php

namespace App\Interface;

interface CardValidationInterface
{
    public function isEuropeIssuedCard(string $countryCode): bool;
}