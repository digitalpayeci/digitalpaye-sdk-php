<?php

namespace DigitalpayeSdkPhp\Config;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DigitalpayeConfig
{
    private $apiKey;
    private $apiSecret;
    private $httpClient;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->httpClient = new Client([
            'base_uri' => Constants::API_BASE_URL . '/' . Constants::VERSION . '/',
            'timeout'  => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret)
            ]
        ]);
    }

    public function getTokenConfig()
    {
        try {
            $response = $this->httpClient->post('auth');
            $data = json_decode($response->getBody()->getContents(), true);
            $token = $data['data']['token'] ?? null;
            return $token;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorMessage, true);
                return json_encode($errorData);
            } else {
                throw new \Exception("Request Exception: " . $e->getMessage());
            }
        }
    }
}
