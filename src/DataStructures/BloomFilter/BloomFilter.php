<?php

namespace Tateren\PhpAlgorithms\DataStructures\BloomFilter;

class BloomFilter
{
    private int $size;
    /**
     * @var object
     * @methods int getValue(int $index)
     * @methods void setValue(int $index)
     */
    private object $storage;

    public function __construct(int $size = 100)
    {
        $this->size = $size;
        $this->storage = $this->createStore($size);
    }

    /**
     * @param $item
     */
    public function insert($item): void
    {
        $hashValues = $this->getHashValues($item);
        foreach ($hashValues as $hashValue) {
            $this->storage->setValue($hashValue);
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function mayContain($item): bool
    {
        $hashValues = $this->getHashValues($item);
        foreach ($hashValues as $hashValue) {
            if ($this->storage->getValue($hashValue) !== true) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $size
     * @return object
     */
    public function createStore($size): object
    {
        return new class ($size) {
            private array $storage;

            public function __construct($size)
            {
                $this->storage = array_fill(0, $size, false);
            }

            public function getValue(int $index): bool
            {
                return $this->storage[$index];
            }

            public function setValue(int $index): void
            {
                $this->storage[$index] = true;
            }
        };
    }

    /**
     * @param $item
     * @return int
     */
    public function hash1($item): int
    {
        $hash = 0;
        foreach (str_split($item) as $char) {
            $char = ord($char);
            $hash = ($hash << 5) + $hash + $char;
            $hash &= $hash;
            $hash = abs($hash);
        }
        return $hash % $this->size;
    }

    /**
     * @param $item
     * @return int
     */
    public function hash2($item): int
    {
        $hash = 5381;
        foreach (str_split($item) as $char) {
            $char = ord($char);
            $hash = ($hash << 5) + $hash + $char;
        }
        return abs($hash % $this->size);
    }

    /**
     * @param $item
     * @return int
     */
    public function hash3($item): int
    {
        $hash = 0;
        foreach (str_split($item) as $char) {
            $char = ord($char);
            $hash = ($hash << 5) - $hash;
            $hash += $char;
            $hash &= $hash;
        }
        return abs($hash % $this->size);
    }

    /**
     * @param $item
     * @return int[]
     */
    public function getHashValues($item): array
    {
        return [
            $this->hash1($item),
            $this->hash2($item),
            $this->hash3($item),
        ];
    }
}
