#!/usr/bin/env php
<?php require_once __dir__ . '/vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pathImportLogDir = __DIR__ . '/parser_test/';
$pathAppCodesIni = __DIR__ . '/parser_test/appCodes.ini';
$pathOutputCsv = __DIR__ . '/output_csv';
$pathOutputCsvOneFile = __DIR__ . '/output_csv_one_file';

$tagsByGroup = [
    'subscription_status' => [
        'active_subscriber',
        'expired_subscriber',
        'never_subscribed',
        'subscription_unknown'
    ],
    'has_downloaded_free_product_status' => [
        'has_downloaded_free_product',
        'not_downloaded_free_product',
        'downloaded_free_product_unknown'
    ],
    'has_downloaded_iap_product_status' => [
        'has_downloaded_iap_product',
        'not_downloaded_free_product',
        'downloaded_iap_product_unknown'
    ]
];

$appCodes = new NShiell\FuturePlc\DataParser\AppCodesReaderIniKeysByTitle($pathAppCodesIni);
$filePaths = new NShiell\FuturePlc\DataParser\LogFileLoader($pathImportLogDir);
$csvReader = new NShiell\FuturePlc\DataParser\CsvReader;

$tagGroupConsolidater = new NShiell\FuturePlc\DataParser\TagGroupConsolidater($tagsByGroup);

$parser = new NShiell\FuturePlc\DataParser\Parser(
    $filePaths,
    $csvReader,
    $tagGroupConsolidater,
    $appCodes
);

$csvWriterFuture = new NShiell\FuturePlc\DataParser\CsvWriterFuture($pathOutputCsv, $pathImportLogDir);
$parser->parse($csvWriterFuture);

$csvWriterFutureOneFile = new NShiell\FuturePlc\DataParser\CsvWriterFutureOneFile($pathOutputCsvOneFile);
$parser->parse($csvWriterFutureOneFile);
