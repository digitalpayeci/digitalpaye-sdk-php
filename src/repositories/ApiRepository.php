<?php

namespace DigitalpayeSdkPhp\Repositories;

use DigitalpayeSdkPhp\Config\DigitalpayeConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DigitalpayeSdkPhp\Config\Constants;


class ApiRepository
{
    private $httpClient;

    public function __construct(DigitalpayeConfig $config)
    {
        $token = $config->getTokenConfig();
        $this->httpClient = new Client([
            'base_uri' => Constants::API_BASE_URL . '/' . Constants::VERSION . '/',
            'timeout'  => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
    }

    public function getToken(): string
    {
        $authorizationHeader = $this->httpClient->getConfig('headers')['Authorization'] ?? '';
        $token = str_replace('Bearer ', '', $authorizationHeader);
        return $token;
    }
    public function postRequest(string $endpoint, array $payload): array
    {
        try {
            $response = $this->httpClient->post($endpoint, [
                'json' => $payload
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    public function getRequest(string $endpoint): array
    {
        try {
            $response = $this->httpClient->get($endpoint);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    private function handleRequestException(RequestException $e): void
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorMessage = $e->getResponse()->getBody()->getContents();
            throw new \Exception("HTTP Error {$statusCode}: {$errorMessage}");
        } else {
            throw new \Exception("Request Exception: " . $e->getMessage());
        }
    }
}
