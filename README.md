# Digitalpaye SDK PHP


## Description

Le Digitalpaye SDK PHP est un SDK Laravel qui permet aux développeurs d'interagir avec l'API Digitalpaye de manière transparente depuis des applications PHP. Ce SDK simplifie le processus d'intégration et fournit des méthodes pour effectuer diverses opérations telles que la vérification des soldes, la création de demandes de collecte et l'initiation de transferts.

## Installation

Vous pouvez installer le Digitalpaye SDK PHP via Composer en exécutant la commande suivante :

```bash
  composer require digitalpaye/digitalpaye-sdk-php
```

Alternativement, vous pouvez télécharger le SDK directement depuis GitHub : Digitalpaye SDK PHP.

## Démarrage

### Get balance

```code
  <?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";
$config = new Digitalpaye($apikey, $apisecret);
//Get balance
$balance = $config->getBalance();
echo($balance["balance"]);
?>
```

### Créer une transaction Wave

```code
  <?php
require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);
//Create Collecte Wave
$dataCreateCollecteWave = array(
    "code_country" => "CI",
    "operator"=> "WAVE_CI",
    "currency"=> "XOF",
    "url_success" => "https://digitalpaye.com",
    "url_error" => "https://digitalpaye.com",
    "url_return"=>  "https://digitalpaye.com",
    "customer_id"=> "0777101308",
    "amount"=> 310,
    "name_user"=> "GUEI HELIE",
    "transaction_id"=> "10180120"
);
$collecteWave = $config->createCollecteWave($dataCreateCollecteWave);
if($collecteWave["status"]=="PENDING"){
    header($collecteWave["url_payment"]);
}else{
    echo($collecteWave["message"]);
}
?>
```

### Créer une transaction MTN Money

```code
  <?php

require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);

//Create Collecte MTN Mobile Money
$dataCreateCollecteMTN= array(
    "code_country" => "CI",
    "operator"=> "MTN_MONEY_CI",
    "currency"=> "XOF",
    "customer_id"=> "0546573332",
    "amount"=> 310,
    "name_user"=> "GUEI HELIE",
    "transaction_id"=> "10180120"
);
$collecteMTN = $config->createCollecteMTN($dataCreateCollecteMTN);
if($collecteMTN["status"]=="PENDING"){
    echo("La transaction est en cours de confirmation");
}else if($collecteMTN["status"]=="FAILED"){
    echo("La transaction a échouée");
}else{
    echo($collecteMTN["message"]);
}
?>
```

### Obtenir le status d'une transaction

```code
 <?php

require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$transactionId = "f511e4f4-d932-4fcd-a804-51539700d60c";
$config = new Digitalpaye($apikey, $apisecret);
//Get Status transaction
$getStatusTransaction = $config->getStatus($transactionId);
if($getStatusTransaction["code_status"]==202){
    echo("La transaction est en cours de validation");
}else if($getStatusTransaction["code_status"]==200){
    echo("La transaction a été validé");
}else{
    echo($getStatusTransaction["message"]);
}
?>
```

### Obtenir toutes les transactions

```code
<?php

require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);
//Get all transactions
$transactions = $config->getAllTransactions();
//Convertir en Json
$jsonTransactions = json_encode($transactions);
echo($jsonTransactions);
?>
```

### Faire un transfert d'argent

```code
<?php

require_once __DIR__ . '/../vendor/autoload.php';
use DigitalpayeSdkPhp\Services\Digitalpaye;

$apikey = "live_digitalpaye129923061";
$apisecret = "d511e1f4-d932-32fcd-a804-371539700d60c";

$config = new Digitalpaye($apikey, $apisecret);

///Create Transfert

$dataCreateTransfer = array(
    "code_country" => "CI",
    "currency"=> "XOF",
    "customer_id"=> "0777101308",
    "amount"=> 310,
    "name"=> "GUEI HELIE",
    "operator"=> "WAVE_CI",
    "transaction_id"=> "10180120"
);
$transfer = $config->createTransfert($dataCreateTransfer);
if($transfer["code_status"]=="SUCESSFUL"){
    echo("Le transfert a été validé");
}else if($transfer["code_status"]=="PENDING"){
    echo("Le transfert est en cours");
}else{
    echo("Le transfert a echoué");
}

?>
```