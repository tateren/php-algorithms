<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\SegmentTree;

use Tateren\PhpAlgorithms\Algorithms\Math\IsPowerOfTwo\IsPowerOfTwo;

class SegmentTree
{
    private array $inputArray;
    /**
     * @var callable
     */
    private $operation;
    /**
     * @var float|int
     */
    private $operationFallback;
    /**
     * @var mixed
     */
    public $segmentTree;

    /**
     * @param array $inputArray
     * @param callable $operation
     * @param float|int $operationFallback
     */
    public function __construct(array $inputArray, callable $operation, $operationFallback)
    {
        $this->inputArray = $inputArray;
        $this->operation = $operation;
        $this->operationFallback = $operationFallback;

        $this->segmentTree = $this->initSegmentTree($this->inputArray);

        $this->buildSegmentTree();
    }

    /**
     * @param array $inputArray
     * @return array
     */
    public function initSegmentTree(array $inputArray): array
    {
        $inputArrayLength = count($inputArray);

        if (IsPowerOfTwo::isPowerOfTwo($inputArrayLength)) {
            $segmentTreeArrayLength = (2 * $inputArrayLength) - 1;
        } else {
            $currentPower = floor(log($inputArrayLength, 2));
            $nextPower = $currentPower + 1;
            $nextPowerOfTwoNumber = 2 ** $nextPower;
            $segmentTreeArrayLength = (2 * $nextPowerOfTwoNumber) - 1;
        }
        return array_fill(0, (int)$segmentTreeArrayLength, null);
    }

    public function buildSegmentTree(): void
    {
        $leftIndex = 0;
        $rightIndex = count($this->inputArray) - 1;
        $position = 0;
        $this->buildTreeRecursively($leftIndex, $rightIndex, $position);
    }

    /**
     * @param int $leftInputIndex
     * @param int $rightInputIndex
     * @param int $position
     */
    public function buildTreeRecursively(int $leftInputIndex, int $rightInputIndex, int $position): void
    {
        if ($leftInputIndex === $rightInputIndex) {
            $this->segmentTree[$position] = $this->inputArray[$leftInputIndex];
            return;
        }

        $middleIndex = (int)floor(($leftInputIndex + $rightInputIndex) / 2);
        $this->buildTreeRecursively($leftInputIndex, $middleIndex, $this->getLeftChildIndex($position));
        $this->buildTreeRecursively($middleIndex + 1, $rightInputIndex, $this->getRightChildIndex($position));

        $this->segmentTree[$position] = ($this->operation)(
            $this->segmentTree[$this->getLeftChildIndex($position)],
            $this->segmentTree[$this->getRightChildIndex($position)],
        );
    }

    /**
     * @param int $queryLeftIndex
     * @param int $queryRightIndex
     * @return float|int|mixed
     */
    public function rangeQuery(int $queryLeftIndex, int $queryRightIndex)
    {
        $leftIndex = 0;
        $rightIndex = count($this->inputArray) - 1;
        $position = 0;

        return $this->rangeQueryRecursive(
            $queryLeftIndex,
            $queryRightIndex,
            $leftIndex,
            $rightIndex,
            $position,
        );
    }

    /**
     * @param int $queryLeftIndex
     * @param int $queryRightIndex
     * @param int $leftIndex
     * @param int $rightIndex
     * @param int $position
     * @return float|int|mixed
     */
    public function rangeQueryRecursive(int $queryLeftIndex, int $queryRightIndex, int $leftIndex, int $rightIndex, int $position)
    {
        if ($queryLeftIndex <= $leftIndex && $queryRightIndex >= $rightIndex) {
            return $this->segmentTree[$position];
        }

        if ($queryLeftIndex > $rightIndex || $queryRightIndex < $leftIndex) {
            return $this->operationFallback;
        }

        $middleIndex = (int)floor(($leftIndex + $rightIndex) / 2);

        $leftOperationResult = $this->rangeQueryRecursive(
            $queryLeftIndex,
            $queryRightIndex,
            $leftIndex,
            $middleIndex,
            $this->getLeftChildIndex($position),
        );

        $rightOperationResult = $this->rangeQueryRecursive(
            $queryLeftIndex,
            $queryRightIndex,
            $middleIndex + 1,
            $rightIndex,
            $this->getRightChildIndex($position),
        );

        return ($this->operation)($leftOperationResult, $rightOperationResult);
    }

    /**
     * @param int $parentIndex
     * @return int
     */
    public function getLeftChildIndex(int $parentIndex): int
    {
        return (2 * $parentIndex) + 1;
    }

    /**
     * @param int $parentIndex
     * @return int
     */
    public function getRightChildIndex(int $parentIndex): int
    {
        return (2 * $parentIndex) + 2;
    }
}
