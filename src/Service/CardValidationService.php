<?php

namespace App\Service;

use App\Interface\CardValidationInterface;
use App\Traits\CardValidationHelper;

class CardValidationService implements CardValidationInterface
{
    use CardValidationHelper;
}