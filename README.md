# Digitalpaye SDK PHP

## Description

Le **Digitalpaye SDK PHP** est un SDK Laravel qui permet aux développeurs d'interagir avec l'API Digitalpaye de manière transparente depuis des applications PHP. Ce SDK simplifie le processus d'intégration et fournit des méthodes pour effectuer diverses opérations telles que :

- La vérification des soldes
- La création de demandes de collecte
- L'initiation de transferts

## Installation

Vous pouvez installer le **Digitalpaye SDK PHP** via Composer en exécutant la commande suivante :

```bash
composer require digitalpaye/digitalpaye-sdk-php
```

Alternativement, vous pouvez télécharger le SDK directement depuis GitHub : **[Digitalpaye SDK PHP](https://github.com/digitalpaye/digitalpaye-sdk-php)**.

## Démarrage

### Obtenir le solde

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);
$balance = $config->getBalance();
echo $balance["data"]["amount"];
?>
```

### Créer une transaction Orange Money

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);

$dataCreateCollecteOrangeMoney = [
    "transactionId" => "DIGITAL-303311901120870",
    "customer" => [
        "lastName" => "GUEI",
        "firstName" => "HELIE",
        "phone" => "0777101308",
        "email" => "elieguei225@gmail.com",
        "address" => [
            "countryCode" => "CI",
            "city" => "Abidjan",
            "streetAddress" => "Plateau Cocody"
        ]
    ],
    "payer" => "0777101308",
    "otpCode" => "4008",
    "amount" => "2000",
    "currency" => "XOF",
    "operator" => "ORANGE_MONEY_CI"
];

$collecteOrangeMoney = $config->createPayment($dataCreateCollecteOrangeMoney);

if ($collecteOrangeMoney['data']["status"] == "PENDING") {
    echo "La transaction est en cours de confirmation";
} elseif ($collecteOrangeMoney['data']["status"] == "SUCCESSFUL") {
    echo "La transaction a été traitée avec succès.";
} else {
    echo $collecteOrangeMoney["message"];
}
?>
```

### Créer une transaction Wave

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);

$dataCreateCollecteWave = [
    "transactionId" => "DIGITAL-303311901120870",
    "customer" => [
        "lastName" => "GUEI",
        "firstName" => "HELIE",
        "phone" => "0777101308",
        "email" => "elieguei225@gmail.com",
        "address" => [
            "countryCode" => "CI",
            "city" => "Abidjan",
            "streetAddress" => "Plateau Cocody"
        ]
    ],
    "payer" => "0546573332",
    "amount" => "2000",
    "urlSuccess" => "https://digitalpaye.com",
    "urlError" => "https://digitalpaye.com",
    "currency" => "XOF",
    "operator" => "WAVE_MONEY_CI"
];

$collecteWave = $config->createPayment($dataCreateCollecteWave);

if ($collecteWave['data']["status"] == "PENDING") {
    header("Location: " . $collecteWave["data"]["waveLaunchUrl"]);
} else {
    echo $collecteWave["message"];
}
?>
```

### Obtenir le statut d'une transaction

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$transactionId = "f511e4f4-d932-4fcd-a804-51539700d60c";
$config = new Digitalpaye($apikey, $apisecret);

$getStatusTransaction = $config->getStatus($transactionId);

if ($getStatusTransaction["data"]["status"] == "PENDING") {
    echo "La transaction est en cours de validation";
} elseif ($getStatusTransaction["data"]["status"] == "SUCCESSFUL") {
    echo "La transaction a été validée";
} else {
    echo $getStatusTransaction["message"];
}
?>
```

### Faire un transfert d'argent

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);

$dataCreateTransfer = [
    "transactionId" => "DIGITAL-0139601181902",
    "customer" => [
        "lastName" => "GUEI",
        "firstName" => "HELIE",
        "phone" => "0777101308",
        "address" => [
            "countryCode" => "CI",
            "city" => "Abidjan",
            "streetAddress" => "Yopougon, Carrefour Canal"
        ]
    ],
    "recipient" => "0777101308",
    "amount" => "100",
    "currency" => "XOF",
    "operator" => "WAVE_MONEY_CI"
];

$transfer = $config->createTransfert($dataCreateTransfer);

if ($transfer['data']["status"] == "SUCCESSFUL") {
    echo "Le transfert a été validé";
} elseif ($transfer['data']["status"] == "PENDING") {
    echo "Le transfert est en cours";
} else {
    echo "Le transfert a échoué";
}
?>
```
