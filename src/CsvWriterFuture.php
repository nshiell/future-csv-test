<?php
namespace NShiell\FuturePlc\DataParser;

class CsvWriterFuture extends AbstractCsvWriterFuture
    implements WriterNewFileEachSourceInterface
{
    private const FILE_PREFIX = 'future-plc-parsed-';
    private readonly int $pathFirstChar;

    private int $id = 0;

    public function __construct(
        private readonly string $pathOutputCsv,
        string $pathImportLogDir
    ) {
        $this->pathFirstChar = strlen($pathImportLogDir);
    }

    private function createNewFilePath(string $sourceFilePath): string
    {
        $sourceDirName = dirname($sourceFilePath);
        $outputFilePath = substr($sourceDirName, $this->pathFirstChar);
        $filename = self::FILE_PREFIX . basename($sourceFilePath);

        $outputPath = $outputFilePath . '/' . $filename;

        return str_replace(
            '//',
            '/',
            $this->pathOutputCsv . '/' . $outputPath
        );
    }

    public function createFileForSource(string $sourceFilePath)// resource
    {
        if ($this->complete) {
            throw new \BadMethodCallException('Writer has already been sealed');
        }

        $filePath = $this->createNewFilePath($sourceFilePath);
        $dir = dirname($filePath);
        $this->createDir($dir);

        $handle = fopen($filePath, 'w');
        fputcsv($handle, self::HEADERS);

        return $handle;
    }

    public function closeFile($fileHandle): void
    {
        if (is_resource($fileHandle)) {
            fclose($fileHandle);
        }
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
        if (!is_resource($fileHandle)) {
            throw new \InvalidArgumentException('Not a resource');
        }

        $fields = func_get_args();
        array_shift($fields);
        fputcsv($fileHandle, $fields);
    }
}