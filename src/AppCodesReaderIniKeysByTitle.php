<?php
namespace NShiell\FuturePlc\DataParser;

class AppCodesReaderIniKeysByTitle extends AppCodesReaderIni
    implements \ArrayAccess
{
    private ?array $valuesByTitle = null;

    public function getArrayByTitle(): array
    {
        if ($this->valuesByTitle === null) {
            $this->valuesByTitle = array_flip($this->getArray());
        }

        return $this->valuesByTitle;
    }


    public function offsetSet($offset, $value): void {
        $array = $this->getArrayByTitle();

        if (is_null($offset)) {
            $array[] = $value;
        } else {
            $array[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool {
        return isset($this->getArrayByTitle()[$offset]);
    }

    public function offsetUnset($offset): void {
        throw new \BadMethodCallException('Not implemented');
    }

    public function offsetGet($offset): mixed {
        $array = $this->getArrayByTitle();
        return isset($array[$offset]) ? $array[$offset] : null;
    }
}