<?php

namespace Tateren\PhpAlgorithms\DataStructures\DisjointSet;

class DisjointSetItem
{
    /**
     * @var mixed $value
     */
    private $value;
    /**
     * @var callable
     */
    private $keyCallback;
    private ?self $parent;
    private array $children;

    /**
     * @param $value
     * @param callable|null $keyCallback
     */
    public function __construct($value, ?callable $keyCallback = null)
    {
        $this->value = $value;
        $this->keyCallback = $keyCallback;
        $this->parent = null;
        $this->children = [];
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        if ($this->keyCallback) {
            return ($this->keyCallback)($this->value);
        }
        return $this->value;
    }

    /**
     * @return $this
     */
    public function getRoot(): self
    {
        return $this->isRoot() ? $this : $this->parent->getRoot();
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->parent === null;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        if (count($this->getChildren()) === 0) {
            return 0;
        }

        $rank = 0;

        foreach ($this->getChildren() as $child) {
            $rank += $child->getRank() + 1;
        }
        return $rank;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return array_values($this->children);
    }

    /**
     * @param DisjointSetItem $parentItem
     * @param bool $forceSettingParentChild
     * @return $this
     */
    public function setParent(DisjointSetItem $parentItem, bool $forceSettingParentChild = true): self
    {
        $this->parent = $parentItem;
        if ($forceSettingParentChild) {
            $parentItem->addChild($this);
        }
        return $this;
    }

    /**
     * @param DisjointSetItem $childItem
     * @return $this
     */
    public function addChild(DisjointSetItem $childItem): self
    {
        $this->children[$childItem->getKey()] = $childItem;
        $childItem->setParent($this, false);
        return $this;
    }
}
