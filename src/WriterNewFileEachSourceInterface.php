<?php
namespace NShiell\FuturePlc\DataParser;

interface WriterNewFileEachSourceInterface
{
    public function createFileForSource(string $sourceFilePath);// resource
}