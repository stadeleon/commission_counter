<?php

namespace App\Interface;

use App\Entity\Card;

interface CardValidatorInterface
{
    public function setCard(Card $card): void;

    public function isEuropeIssuedCard(): bool;
}