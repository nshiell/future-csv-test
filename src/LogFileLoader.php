<?php
namespace NShiell\FuturePlc\DataParser;

class LogFileLoader implements \IteratorAggregate
{
    private ?array $filePaths = null;
    public function __construct(private readonly string $pathImportLogDir) {}


    public function getAllFilePaths(): array
    {
        if ($this->filePaths === null) {
            $this->buildFilePathsArray($this->pathImportLogDir);
        }

        return $this->filePaths;
    }

    private function buildFilePathsArray($dir): void
    {
        if (!is_dir($dir)) {
            throw new \BadMethodCallException('File paths is not valid');
        }

        $entries = scandir($dir);

        foreach ($entries as $entry) {
            if ($entry[0] == '.') {
                continue;
            }

            $entryPath = str_replace('//', '/', $dir . '/' . $entry);
            if (is_dir($entryPath)) {
                $this->buildFilePathsArray($entryPath);
            } elseif (strtolower(substr($entry, -4)) == '.log') {
                $this->filePaths[] = $entryPath;
            }
        }
    }



    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getAllFilePaths());
    }

}