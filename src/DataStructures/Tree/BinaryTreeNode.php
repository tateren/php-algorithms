<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree;

use Tateren\PhpAlgorithms\DataStructures\HashTable\HashTable;
use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class BinaryTreeNode
{
    /**
     * @var ?BinaryTreeNode
     */
    public ?BinaryTreeNode $left;
    /**
     * @var ?BinaryTreeNode
     */
    public ?BinaryTreeNode $right;
    /**
     * @var ?BinaryTreeNode
     */
    public ?BinaryTreeNode $parent;
    /**
     * @var mixed|null
     */
    public $value;
    /**
     * @var HashTable
     */
    public HashTable $meta;
    /**
     * @var Comparator
     */
    public Comparator $nodeComparator;

    /**
     * @param null $value
     */
    public function __construct($value = null)
    {
        $this->left = null;
        $this->right = null;
        $this->parent = null;
        $this->value = $value;
        $this->meta = new HashTable();
        $this->nodeComparator = new Comparator();
    }

    /**
     * @return int
     */
    private function leftHeight(): int
    {
        if (!$this->left) {
            return 0;
        }

        return $this->left->height() + 1;
    }

    /**
     * @return int
     */
    private function rightHeight(): int
    {
        if (!$this->right) {
            return 0;
        }

        return $this->right->height() + 1;
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return max($this->leftHeight(), $this->rightHeight());
    }

    /**
     * @return int
     */
    public function balanceFactor(): int
    {
        return $this->leftHeight() - $this->rightHeight();
    }

    /**
     * @return $this|null
     */
    public function uncle(): ?self
    {
        if (!$this->parent) {
            return null;
        }

        if (!$this->parent->parent) {
            return null;
        }

        if (!$this->parent->parent->left || !$this->parent->parent->right) {
            return null;
        }

        if ($this->nodeComparator->equal($this->parent, $this->parent->parent->left)) {
            return $this->parent->parent->right;
        }

        return $this->parent->parent->left;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param ?BinaryTreeNode $node
     * @return $this
     */
    public function setLeft(self $node = null): self
    {
        if ($this->left) {
            $this->left->parent = null;
        }

        $this->left = $node;

        if ($this->left) {
            $this->left->parent = $this;
        }

        return $this;
    }

    /**
     * @param ?BinaryTreeNode $node
     * @return $this
     */
    public function setRight(self $node = null): self
    {
        if ($this->right) {
            $this->right->parent = null;
        }

        $this->right = $node;

        if ($node) {
            $this->right->parent = $this;
        }

        return $this;
    }

    /**
     * @param ?BinaryTreeNode $nodeToRemove
     * @return bool
     */
    public function removeChild(self $nodeToRemove = null): bool
    {
        if ($this->left && $this->nodeComparator->equal($this->left, $nodeToRemove)) {
            $this->left = null;
            return true;
        }

        if ($this->right && $this->nodeComparator->equal($this->right, $nodeToRemove)) {
            $this->right = null;
            return true;
        }

        return false;
    }

    /**
     * @param ?BinaryTreeNode $nodeToReplace
     * @param ?BinaryTreeNode $replacementNode
     * @return bool
     */
    public function replaceChild(self $nodeToReplace = null, self $replacementNode = null): bool
    {
        if (!$nodeToReplace || !$replacementNode) {
            return false;
        }

        if ($this->left && $this->nodeComparator->equal($this->left, $nodeToReplace)) {
            $this->left = $replacementNode;
            return true;
        }

        if ($this->right && $this->nodeComparator->equal($this->right, $nodeToReplace)) {
            $this->right = $replacementNode;
            return true;
        }

        return false;
    }

    /**
     * @param BinaryTreeNode $sourceNode
     * @param BinaryTreeNode $targetNode
     */
    public static function copyNode(self $sourceNode, self $targetNode): void
    {
        $targetNode->setValue($sourceNode->value);
        $targetNode->setLeft($sourceNode->left);
        $targetNode->setRight($sourceNode->right);
    }

    /**
     * @return array
     */
    public function traverseInOrder(): array
    {
        $traverse = [];

        if ($this->left) {
            $traverse = array_merge($traverse, $this->left->traverseInOrder());
        }

        $traverse[] = $this->value;

        if ($this->right) {
            $traverse = array_merge($traverse, $this->right->traverseInOrder());
        }

        return $traverse;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', $this->traverseInOrder());
    }
}
