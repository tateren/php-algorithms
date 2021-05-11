<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\FenwickTree;

use Exception;

class FenwickTree
{
    private int $arraySize;
    public array $treeArray;

    /**
     * @param int $arraySize
     */
    public function __construct(int $arraySize)
    {
        $this->arraySize = $arraySize;
        $this->treeArray = array_fill(0, $this->arraySize + 1, 0);
    }

    /**
     * @param int $position
     * @param int $value
     * @return $this
     * @throws Exception
     */
    public function increase(int $position, int $value): self
    {
        if ($position < 1 || $position > $this->arraySize) {
            throw new Exception('Position is out of allowed range');
        }

        for ($i = $position; $i <= $this->arraySize; $i += ($i & -$i)) {
            $this->treeArray[$i] += $value;
        }

        return $this;
    }

    /**
     * @param int $position
     * @return int
     * @throws Exception
     */
    public function query(int $position): int
    {
        if ($position < 1 || $position > $this->arraySize) {
            throw new Exception('Position is out of allowed range');
        }

        $sum = 0;

        for ($i = $position; $i > 0; $i -= ($i & -$i)) {
            $sum += $this->treeArray[$i];
        }

        return $sum;
    }

    /**
     * @param $leftIndex
     * @param $rightIndex
     * @return int
     * @throws Exception
     */
    public function queryRange($leftIndex, $rightIndex): int
    {
        if ($leftIndex > $rightIndex) {
            throw new Exception('Left index can not be greater than right one');
        }

        if ($leftIndex === 1) {
            return $this->query($rightIndex);
        }

        return $this->query($rightIndex) - $this->query($leftIndex - 1);
    }
}
