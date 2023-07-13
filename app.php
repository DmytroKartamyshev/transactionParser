<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('BIN_API_URL')->notEmpty();
$dotenv->required('EXCHANGE_RATE_API_URL')->notEmpty();

use Src\Transaction\TransactionParser;
use Src\Services\Bin\BinService;
use Src\Services\ExchangeRate\ExchangeRateService;
use Src\Services\Bin\BinException;
use Src\Services\ExchangeRate\ExchangeRateException;


$inputFile = $argv[1] ?? null;

try {

    $transactionParser = new TransactionParser($inputFile);

    $transactions = $transactionParser->parse();

    foreach ($transactions as $transaction) {
        $binService = new BinService($transaction->getBinCode());
        $exchangeRateService = new ExchangeRateService();

        echo $transaction->calcCommissionAmount($binService, $exchangeRateService) . '\n';
    }
} catch (BinException $binException) {
    print('Bin Service Error: ' . $binException->getMessage());
} catch (ExchangeRateException $exchangeRateException) {
    print('Exchange Rate Service Error: ' . $exchangeRateException->getMessage());
} catch (\Exception $e) {
    print($e->getMessage());
}