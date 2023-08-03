<?php

namespace App\DataProvider;

use stdClass;

class JsonHttpDataProvider extends AbstractHttpDataProvider
{
    public function getJson(): stdClass
    {
        $response = parent::get();
        return json_decode($response);
    }
    public function setGetRequestParams(array $params): void
    {
        foreach ($params as $key => $value) {
            $this->getParams[] = "{$key}={$value}";
        }
    }

    public function setInlineRequestParams(array $params): void
    {
        $this->inlineParams = '';

        foreach ($params as $value) {
            $this->inlineParams .= "/{$value}";
        }

        $this->inlineParams = trim($this->inlineParams, '/');
    }

    public function setHeaders(array $headersArray): void
    {
        $this->headers = [];
        foreach ($headersArray as $key => $value) {
            $this->headers[] = "{$key}: {$value}";
        }
    }
}