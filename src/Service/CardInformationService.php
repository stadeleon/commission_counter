<?php

namespace App\Service;

use App\DataProvider\AbstractHttpDataProvider;
use App\Interface\CardInformationProviderInterface;
use stdClass;

class CardInformationService implements CardInformationProviderInterface
{
    public function __construct(private readonly AbstractHttpDataProvider $dataProvider)
    {
    }

    public function getCardInformation(string $cardNumber): stdClass
    {
        $this->dataProvider->setInlineRequestParams([$cardNumber]);
        $headers = ["Content-Type" => "text/plain", "Accept" => "application/json"];
        $this->dataProvider->setHeaders($headers);

        return $this->dataProvider->getJson();
    }
}