<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Heap;

class MaxHeap extends Heap
{
    /**
     * @param $firstElement
     * @param $secondElement
     * @return bool
     */
    public function pairIsInCorrectOrder($firstElement, $secondElement): bool
    {
        return $this->compare->greaterThan($firstElement, $secondElement);
    }
}
