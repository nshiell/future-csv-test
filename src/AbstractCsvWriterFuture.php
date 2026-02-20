<?php
namespace NShiell\FuturePlc\DataParser;

abstract class AbstractCsvWriterFuture
{
    protected bool $complete = false;
    protected const HEADERS = [
        'id',
        'appCode',
        'deviceId',
        'contactable',
        'subscription_status',
        'has_downloaded_free_product_status',
        'has_downloaded_iap_product_status',
        'bad_tags'
    ];

    protected function createDir(string $dir): void
    {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new \RuntimeException(
                    'Unable to create directory: ' . $dir
                );
            }
        }
    }

    public function completed(): void
    {
        $this->complete = true;
    }
}