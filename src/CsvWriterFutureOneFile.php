<?php
namespace NShiell\FuturePlc\DataParser;

class CsvWriterFutureOneFile extends AbstractCsvWriterFuture
{
    private $fileHandle;
    private const FILE_NAME = 'future-plc-parsed.csv';

    private int $id = 0;

    public function __construct(private readonly string $pathOutputCsv) {}

    private function createNewFilePath(): string
    {
        return str_replace(
            '//',
            '/',
            $this->pathOutputCsv . '/' . self::FILE_NAME
        );
    }

    public function createFile()// resource
    {
        if (!$this->fileHandle) {
            if ($this->complete) {
                throw new \BadMethodCallException(
                    'Writer has already been sealed'
                );
            }

            $filePath = $this->createNewFilePath();
            $dir = dirname($filePath);
            $this->createDir($dir);

            $this->fileHandle = fopen($filePath, 'w');
            fputcsv($this->fileHandle, self::HEADERS);
        }

        return $this->fileHandle;
    }

    public function closeFile(): void
    {
        if (!is_resource($this->fileHandle)) {
            throw new \InvalidArgumentException('Not a resource');
        }

        fclose($this->fileHandle);
    }

    public function write(
        $fileHandle,
        int $id,
        string $appCode,
        string $deviceId,
        int $contactable,
        string $subscription_status,
        string $has_downloaded_free_product_status,
        string $has_downloaded_iap_product_status
    ) {
        if (!is_resource($this->fileHandle)) {
            throw new \InvalidArgumentException('Not a resource');
        }

        $fields = func_get_args();
        array_shift($fields);
        fputcsv($this->fileHandle, $fields);
    }
}