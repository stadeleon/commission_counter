<?php

namespace App\Interface;

use App\Entity\Card;
use stdClass;

interface CardFactoryInterface
{
    public function createCard(stdClass $cardInfo): Card;
}