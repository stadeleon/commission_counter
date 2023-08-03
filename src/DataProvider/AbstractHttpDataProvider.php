<?php

namespace App\DataProvider;

use Exception;
use App\Interface\RequestInterface;

class AbstractHttpDataProvider implements RequestInterface
{
    const GET='GET';
    const POST='POST';
    protected string $inlineParams = '';
    protected array $getParams = [];
    protected array $headers = [];

    public function __construct(private readonly string $baseUrl, private readonly string $requestType)
    {
    }

    private function getRequestUrl(): string
    {
        $url = $this->baseUrl . $this->inlineParams;

        if ($this->getParams) {
            $getParams = implode('&', $this->getParams);
            $url .= "?{$getParams}";
        }

        return $url;
    }

    /**
     * @throws Exception
     */
    public function get(): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getRequestUrl(),
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $this->requestType
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception('HTTP Request failed');
        }

        curl_close($curl);

        return $response;
    }
}