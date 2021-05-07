<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\PriorityQueue;

use Closure;
use Tateren\PhpAlgorithms\DataStructures\Heap\MinHeap;
use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class PriorityQueue extends MinHeap
{
    private array $priorities;
    private Closure $compareValue;

    public function __construct()
    {
        parent::__construct();

        $this->compareValue = function ($a, $b) {
            return $a <=> $b;
        };

        $this->priorities = [];

        $this->compare = new Comparator(function ($a, $b) {
            return $this->priorities[serialize($a)] <=> $this->priorities[serialize($b)];
        });
    }

    /**
     * @param $item
     * @param int $priority
     * @return $this
     */
    public function add($item, int $priority = 0): self
    {
        $this->priorities[serialize($item)] = $priority;
        parent::add($item);
        return $this;
    }

    /**
     * @param $item
     * @param Comparator|null $comparator
     * @return $this
     */
    public function remove($item, Comparator $comparator = null): self
    {
        parent::remove($item, $comparator);
        unset($this->priorities[serialize($item)]);
        return $this;
    }

    /**
     * @param $item
     * @param $priority
     * @return $this
     */
    public function changePriority($item, $priority): self
    {
        $this->remove($item, new Comparator($this->compareValue));
        $this->add($item, $priority);
        return $this;
    }

    /**
     * @param $item
     * @return int[]
     */
    public function findByValue($item): array
    {
        return $this->find($item, new Comparator($this->compareValue));
    }

    /**
     * @param $item
     * @return bool
     */
    public function hasValue($item): bool
    {
        return count($this->findByValue($item)) > 0;
    }
}
