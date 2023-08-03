<?php

namespace App\Service;

use App\DataProvider\AbstractHttpDataProvider;
use App\DataProvider\JsonHttpDataProvider;
use App\Interface\CardInformationProviderInterface;
use stdClass;

class CardInformationProviderService implements CardInformationProviderInterface
{
    private const BASE_URL = "https://lookup.binlist.net/";
    private AbstractHttpDataProvider $dataProvider;

    public function __construct()
    {
        $this->dataProvider = new JsonHttpDataProvider(self::BASE_URL, AbstractHttpDataProvider::GET);
    }

    public function getCardInformation(string $cardNumber): stdClass
    {
        $this->dataProvider->setInlineRequestParams([$cardNumber]);
        $headers = ["Content-Type" => "text/plain", "Accept" => "application/json"];
        $this->dataProvider->setHeaders($headers);

        return $this->dataProvider->getJson();
    }
}