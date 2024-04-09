<?php

namespace DigitalpayeSdkPhp\Config;
use DigitalpayeSdkPhp\Config\Constants;
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
            $data = json_decode($response->getBody(), true);
            return $data['token'] ?? null;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorMessage = $e->getResponse()->getBody()->getContents();
                throw new \Exception("HTTP Error {$statusCode}: {$errorMessage}");
            } else {
                throw new \Exception("Request Exception: " . $e->getMessage());
            }
        }
    }
}
