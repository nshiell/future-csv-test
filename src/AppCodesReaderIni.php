<?php
namespace NShiell\FuturePlc\DataParser;

class AppCodesReaderIni
{
    private ?array $array = null;
    public function __construct(private readonly string $pathAppCodesIni) {}

    private function createArray(): void
    {
        if (!is_file($this->pathAppCodesIni)) {
            throw new \BadMethodCallException(
                'File not found: ' . $this->pathAppCodesIni
            );
        }
        $this->array = parse_ini_file($this->pathAppCodesIni);
        if (!$this->array) {
            throw new \BadMethodCallException(
                'Bad ini file: ' . $this->pathAppCodesIni
            );
        }
    }

    public function getArray(): array
    {
        if ($this->array === null) {
            $this->createArray();
        }

        return $this->array;
    }
}