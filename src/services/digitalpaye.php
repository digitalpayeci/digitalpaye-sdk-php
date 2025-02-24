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
   public function createPayment(array $payload): array
    {
        $requiredKeys = [
            'transactionId', 
            'customer', 
            'amount', 
            'currency', 
            'operator', 
            'payer'
        ];

        // Vérifier les champs supplémentaires en fonction de l'opérateur
        switch ($payload['operator']) {
            case 'ORANGE_MONEY_CI':
                $additionalKeys = ['otpCode'];
                break;
            case 'WAVE_CI':
                $additionalKeys = ['urlSuccess', 'urlError']; // Ces champs existent-ils vraiment ?
                break;
            case 'MTN_MONEY_CI':
                $additionalKeys = [];
                break;
            default:
                throw new \InvalidArgumentException('Invalid operator: ' . $payload['operator']);
        }

        $allRequiredKeys = array_merge($requiredKeys, $additionalKeys);
        $this->validatePayload($payload, $allRequiredKeys);

        // Envoyer la demande de paiement à l'API
        return $this->apiRepository->postRequest('collecte/mobile-money', $payload);
    }

    public function createCollecteCard(array $payload): array
    {
        $this->validatePayload($payload, ['code_country', 'currency', 'customer_id', 'amount', 'name_user',  'email_user', 'transaction_id', 'redirect_url']);
        return $this->apiRepository->postRequest('collecte/card/create', $payload);
    }
    public function getBalance(): array
    {
        return $this->apiRepository->getRequest('balance');
    }

  public function createTransfer(array $payload): array
    {
        $this->validatePayload($payload, [
            'transactionId', 
            'customer', 
            'amount', 
            'currency', 
            'operator', 
            'recipient'
        ]);

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
