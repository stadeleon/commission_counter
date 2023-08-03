<?php

namespace App\Factory;

use App\Entity\Card;
use App\Interface\CardFactoryInterface;
use stdClass;

class CardFactory implements CardFactoryInterface
{

    public function createCard(stdClass $cardInfo): Card
    {
        return new Card($cardInfo);
    }
}