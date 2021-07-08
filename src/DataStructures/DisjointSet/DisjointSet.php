<?php

namespace Tateren\PhpAlgorithms\DataStructures\DisjointSet;

use Exception;

class DisjointSet
{
    /**
     * @var callable?
     */
    private $keyCallback;
    /**
     * @var DisjointSetItem[]
     */
    private array $items;

    /**
     * @param callable|null $keyCallback
     */
    public function __construct(?callable $keyCallback = null)
    {
        $this->keyCallback = $keyCallback;
        $this->items = [];
    }

    /**
     * @param mixed $itemValue
     * @return $this
     */
    public function makeSet($itemValue): self
    {
        $disjointSetItem = new DisjointSetItem($itemValue, $this->keyCallback);

        if (empty($this->items[$disjointSetItem->getKey()])) {
            $this->items[$disjointSetItem->getKey()] = $disjointSetItem;
        }

        return $this;
    }

    /**
     * @param $itemValue
     * @return string|null
     */
    public function find($itemValue): ?string
    {
        $templateDisjointSetItem = new DisjointSetItem($itemValue, $this->keyCallback);

        $requiredDisjointSetItem = $this->items[$templateDisjointSetItem->getKey()] ?? null;

        if (!$requiredDisjointSetItem) {
            return null;
        }

        return $requiredDisjointSetItem->getRoot()->getKey();
    }

    /**
     * @param $valueA
     * @param $valueB
     * @return $this
     * @throws Exception
     */
    public function union($valueA, $valueB): self
    {
        $rootKeyA = $this->find($valueA);
        $rootKeyB = $this->find($valueB);

        if ($rootKeyA === null || $rootKeyB === null) {
            throw new Exception('One or two values are not in sets');
        }

        if ($rootKeyA === $rootKeyB) {
            return $this;
        }

        $rootA = $this->items[$rootKeyA];
        $rootB = $this->items[$rootKeyB];

        if ($rootA->getRank() < $rootB->getRank()) {
            $rootB->addChild($rootA);
            return $this;
        }
        $rootA->addChild($rootB);
        return $this;
    }

    /**
     * @param $valueA
     * @param $valueB
     * @return bool
     * @throws Exception
     */
    public function inSameSet($valueA, $valueB): bool
    {
        $rootKeyA = $this->find($valueA);
        $rootKeyB = $this->find($valueB);

        if ($rootKeyA === null || $rootKeyB === null) {
            throw new Exception('One or two values are not in sets');
        }
        return $rootKeyA === $rootKeyB;
    }
}
