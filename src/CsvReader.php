<?php
namespace NShiell\FuturePlc\DataParser;

class CsvReader
{
    public function read(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        $records = [];
        while (($row = fgetcsv($handle)) !== false) {
            $records[] = array_combine($headers, $row);
        }
        fclose($handle);

        return $records;
    }
}