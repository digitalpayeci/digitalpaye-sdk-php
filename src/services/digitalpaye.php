<?php

namespace DigitalpayeSdkPhp\Services;

use DigitalpayeSdkPhp\Config\DigitalpayeConfig;
use DigitalpayeSdkPhp\Repositories\ApiRepository;

class Digitalpaye
{
    private $apiRepository;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $config = new DigitalpayeConfig($apiKey, $apiSecret);
        $this->apiRepository = new ApiRepository($config);
    }
    public function createCollecteMTN(array $payload): array
    {
        $this->validatePayload($payload, ['code_country', 'operator', 'currency', 'customer_id', 'amount', 'name_user', 'transaction_id']);
        return $this->apiRepository->postRequest('collecte/mobile-money', $payload);
    }

    public function createCollecteWave(array $payload): array
    {
        $this->validatePayload($payload, ['code_country', 'operator', 'currency', 'customer_id', 'amount', 'name_user', 'transaction_id', 'url_success', 'url_error', 'url_return']);
        return $this->apiRepository->postRequest('collecte/mobile-money', $payload);
    }

    public function getBalance(): array
    {
        return $this->apiRepository->getRequest('balance');
    }

    public function createTransfert(array $payload): array
    {
        $this->validatePayload($payload, ['code_country', 'currency', 'customer_id', 'name', 'amount', 'operator', 'transaction_id']);
        return $this->apiRepository->postRequest('transfers/mobile-money', $payload);
    }

    public function getStatus(string $transactionId): array
    {
        return $this->apiRepository->getRequest("get-status-payment/{$transactionId}");
    }

    public function getAllTransactions(): array
    {
        return $this->apiRepository->getRequest('get-all-transactions');
    }

    private function validatePayload(array $payload, array $requiredKeys): void
    {
        $missingKeys = array_diff($requiredKeys, array_keys($payload));
        if (!empty($missingKeys)) {
            throw new \InvalidArgumentException('Missing required payload keys: ' . implode(', ', $missingKeys));
        }
    }
}
