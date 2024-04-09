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
    public function postRequest(string $endpoint, array $payload): array
    {
        try {
            $response = $this->httpClient->post($endpoint, [
                'json' => $payload
            ]);
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);
            if ($statusCode >= 200 && $statusCode < 300) {
                return $responseData;
            } else {
                return $responseData;
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorMessage, true);
                return $errorData;
            } else {
                throw new \Exception("Request Exception: " . $e->getMessage());
            }
        }
    }

    public function getRequest(string $endpoint): array
    {
        try {
            $response = $this->httpClient->get($endpoint);
            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);
            if ($statusCode >= 200 && $statusCode < 300) {
                return $responseData;
            } else {
                return $responseData;
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorMessage, true);
                return $errorData;
            } else {
                throw new \Exception("Request Exception: " . $e->getMessage());
            }
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
