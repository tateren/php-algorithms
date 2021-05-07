<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Heap;

use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

abstract class Heap
{
    private array $heapContainer;
    protected Comparator $compare;

    /**
     * @param ?callable $comparatorFunction
     */
    public function __construct(callable $comparatorFunction = null)
    {
        $this->heapContainer = [];
        $this->compare = new Comparator($comparatorFunction);
    }

    /**
     * @param int $parentIndex
     * @return int
     */
    protected function getLeftChildIndex(int $parentIndex): int
    {
        return (2 * $parentIndex) + 1;
    }

    /**
     * @param int $parentIndex
     * @return int
     */
    protected function getRightChildIndex(int $parentIndex): int
    {
        return (2 * $parentIndex) + 2;
    }

    /**
     * @param int $childIndex
     * @return int
     */
    protected function getParentIndex(int $childIndex): int
    {
        return (int)floor(($childIndex - 1) / 2);
    }

    /**
     * @param int $childIndex
     * @return bool
     */
    protected function hasParent(int $childIndex): bool
    {
        return $this->getParentIndex($childIndex) >= 0;
    }

    /**
     * @param int $parentIndex
     * @return bool
     */
    protected function hasLeftChild(int $parentIndex): bool
    {
        return $this->getLeftChildIndex($parentIndex) < count($this->heapContainer);
    }

    /**
     * @param int $parentIndex
     * @return bool
     */
    protected function hasRightChild(int $parentIndex): bool
    {
        return $this->getRightChildIndex($parentIndex) < count($this->heapContainer);
    }

    /**
     * @param int $parentIndex
     * @return mixed|null
     */
    protected function leftChild(int $parentIndex)
    {
        return $this->heapContainer[$this->getLeftChildIndex($parentIndex)] ?? null;
    }

    /**
     * @param int $parentIndex
     * @return mixed|null
     */
    protected function rightChild(int $parentIndex)
    {
        return $this->heapContainer[$this->getRightChildIndex($parentIndex)] ?? null;
    }

    /**
     * @param int $childIndex
     * @return mixed|null
     */
    protected function parent(int $childIndex)
    {
        return $this->heapContainer[$this->getParentIndex($childIndex)] ?? null;
    }

    /**
     * @param int $indexOne
     * @param int $indexTwo
     */
    protected function swap(int $indexOne, int $indexTwo): void
    {
        [
            $this->heapContainer[$indexTwo],
            $this->heapContainer[$indexOne],
        ] = [
            $this->heapContainer[$indexOne],
            $this->heapContainer[$indexTwo],
        ];
    }

    /**
     * @return mixed|null
     */
    public function peek()
    {
        if (count($this->heapContainer) === 0) {
            return null;
        }

        return $this->heapContainer[0];
    }

    /**
     * @return mixed|null
     */
    public function poll()
    {
        if (count($this->heapContainer) === 0) {
            return null;
        }

        if (count($this->heapContainer) === 1) {
            return array_pop($this->heapContainer);
        }

        $item = $this->heapContainer[0];

        $this->heapContainer[0] = array_pop($this->heapContainer);
        $this->heapifyDown();

        return $item;
    }

    /**
     * @param $item
     * @return $this
     */
    public function add($item): self
    {
        $this->heapContainer[] = $item;
        $this->heapifyUp();
        return $this;
    }

    /**
     * @param $item
     * @param ?Comparator $comparator
     * @return Heap
     */
    public function remove($item, ?Comparator $comparator = null): self
    {
        if (is_null($comparator)) {
            $comparator = $this->compare;
        }
        $numberOfItemsToRemove = count($this->find($item, $comparator));

        for ($iteration = 0; $iteration < $numberOfItemsToRemove; $iteration++) {
            $array = $this->find($item, $comparator);
            $indexToRemove = array_pop($array);

            // If we need to remove last child in the heap then just remove it.
            // There is no need to heapify the heap afterwards.
            if ($indexToRemove === (count($this->heapContainer) - 1)) {
                array_pop($this->heapContainer);
            } else {
                // Move last element in heap to the vacant (removed) position.
                $this->heapContainer[$indexToRemove] = array_pop($this->heapContainer);

                // Get parent.
                $parentItem = $this->parent($indexToRemove);

                // If there is no parent or parent is in correct order with the node
                // we're going to delete then heapify down. Otherwise heapify up.
                if (
                    $this->hasLeftChild($indexToRemove)
                    && (!$parentItem || $this->pairIsInCorrectOrder($parentItem, $this->heapContainer[$indexToRemove]))
                ) {
                    $this->heapifyDown($indexToRemove);
                } else {
                    $this->heapifyUp($indexToRemove);
                }
            }
        }
        return $this;
    }

    /**
     * @param mixed $item
     * @param ?Comparator $comparator
     * @return int[]
     */
    public function find($item, ?Comparator $comparator = null): array
    {
        $foundItemIndices = [];
        if (is_null($comparator)) {
            $comparator = $this->compare;
        }

        foreach ($this->heapContainer as $itemIndex => $itemIndexValue) {
            if ($comparator->equal($item, $itemIndexValue)) {
                $foundItemIndices[] = $itemIndex;
            }
        }

        return $foundItemIndices;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !count($this->heapContainer);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', $this->heapContainer);
    }

    /**
     * @param int|null $customStartIndex
     */
    protected function heapifyUp(int $customStartIndex = null): void
    {
        // Take the last element (last in array or the bottom left in a tree)
        // in the heap container and lift it up until it is in the correct
        // order with respect to its parent element.
        $currentIndex = $customStartIndex ?? (count($this->heapContainer) - 1);
        while (
            $this->hasParent($currentIndex)
            && !$this->pairIsInCorrectOrder($this->parent($currentIndex), $this->heapContainer[$currentIndex])
        ) {
            $this->swap($currentIndex, $this->getParentIndex($currentIndex));
            $currentIndex = $this->getParentIndex($currentIndex);
        }
    }

    /**
     * @param int $customStartIndex
     */
    protected function heapifyDown(int $customStartIndex = 0): void
    {
        // Compare the parent element to its children and swap parent with the appropriate
        // child (smallest child for MinHeap, largest child for MaxHeap).
        // Do the same for next children after swap.
        $currentIndex = $customStartIndex;

        while ($this->hasLeftChild($currentIndex)) {
            if (
                $this->hasRightChild($currentIndex)
                && $this->pairIsInCorrectOrder($this->rightChild($currentIndex), $this->leftChild($currentIndex))
            ) {
                $nextIndex = $this->getRightChildIndex($currentIndex);
            } else {
                $nextIndex = $this->getLeftChildIndex($currentIndex);
            }

            if ($this->pairIsInCorrectOrder($this->heapContainer[$currentIndex], $this->heapContainer[$nextIndex])) {
                break;
            }

            $this->swap($currentIndex, $nextIndex);
            $currentIndex = $nextIndex;
        }
    }

    /**
     * @param $firstElement
     * @param $secondElement
     * @return bool
     */
    abstract public function pairIsInCorrectOrder($firstElement, $secondElement): bool;
}
