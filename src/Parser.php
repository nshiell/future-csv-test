<?php
namespace NShiell\FuturePlc\DataParser;

class Parser
{
    private const NO_DATA = 'FUTURE-UNKNOWN';

    public function __construct(
        private readonly iterable $filePaths,
        private readonly CsvReader $csvReader,
        private readonly TagGroupConsolidater $tagGroupConsolidater,
        private readonly AppCodesReaderIni $appCodes // <-- use an interface?
    ){}

    private function write(
        $outputWriter,
        $fileHandle,
        array $record,
        int $id
    ): void {
        $appCode = $this->appCodes[$record['app']] ?? self::NO_DATA;
        $contactable = (strpos($record['deviceTokenStatus'], '1') !== false)
            ? 1
            : 0;

        $recordTags = array_map('trim', explode('|', $record['tags']));
        $tagGroups = $this->tagGroupConsolidater->createTagGroups($recordTags);


        $outputWriter->write(
            fileHandle: $fileHandle,
            id: $id,
            appCode: $appCode,
            deviceId: $record['deviceTokenStatus'] ?? self::NO_DATA,
            contactable: strpos($record['deviceTokenStatus'], '1') !== false ?? 0,
            subscription_status: $tagGroups['subscription_status'],
            has_downloaded_free_product_status: $tagGroups['has_downloaded_free_product_status'],
            has_downloaded_iap_product_status: $tagGroups['has_downloaded_iap_product_status']
        );
    }

    public function parse($writer): void
    {
        $id = 0;

        $writerNewFileEachSource = (
            $writer instanceof WriterNewFileEachSourceInterface
        );

        if (!$writerNewFileEachSource) {
            $fileHandle = $writer->createFile();
        }

        foreach ($this->filePaths as $sourceFilePath) {
            if ($writerNewFileEachSource) {
                $fileHandle = $writer->createFileForSource($sourceFilePath);
            }

            foreach ($this->csvReader->read($sourceFilePath) as $record) {
                $id++;
                $this->write($writer, $fileHandle, $record, $id);
            }

            if ($writerNewFileEachSource) {
                $writer->closeFile($fileHandle);
            }
        }

        $writer->closeFile($fileHandle);
    }
}