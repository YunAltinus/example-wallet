<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeService
{
    public $client;
    public $baseUri;
    public $apiKey;

    public function __construct()
    {
        $this->apiKey = env("EXCHANGE_API_KEY");
        $this->baseUri = "https://v6.exchangerate-api.com/v6/" . $this->apiKey;
        $this->client = new Client([
            'headers' => [
                'Accept'     => 'application/json',
                'content-type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param string $currency
     * @return array
     */
    public function fetchCurrencyByExchange($currency = "TRY")
    {
        $res = $this->client->get("{$this->baseUri}/latest/{$currency}");

        return json_decode($res->getBody()->getContents(), true);
    }

    public function fetchPairConversion($amount, $fromCurrency = "TRY", $toCurrency = "USD")
    {
        $res = $this->client->get("{$this->baseUri}/pair/{$fromCurrency}/{$toCurrency}/$amount");

        return json_decode($res->getBody()->getContents(), true);
    }
}
