<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree;

use Closure;
use Exception;
use Tateren\PhpAlgorithms\DataStructures\Tree\BinaryTreeNode;
use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class SearchBinaryTreeNode extends BinaryTreeNode
{
    private ?Closure $compareFunction;
    private Comparator $nodeValueComparator;

    /**
     * @param null $value
     * @param callable|null $compareFunction
     */
    public function __construct($value = null, callable $compareFunction = null)
    {
        parent::__construct($value);
        $this->compareFunction = $compareFunction;
        $this->nodeValueComparator = new Comparator($compareFunction);
    }

    /**
     * @param $value
     * @return BinaryTreeNode
     */
    public function insert($value): BinaryTreeNode
    {
        if ($this->nodeValueComparator->equal($this->value, null)) {
            $this->value = $value;

            return $this;
        }

        if ($this->nodeValueComparator->lessThan($value, $this->value)) {
            if ($this->left) {
                return $this->left->insert($value);
            }

            $newNode = new SearchBinaryTreeNode($value, $this->compareFunction);
            $this->setLeft($newNode);

            return $newNode;
        }

        if ($this->nodeValueComparator->greaterThan($value, $this->value)) {
            if ($this->right) {
                return $this->right->insert($value);
            }

            $newNode = new SearchBinaryTreeNode($value, $this->compareFunction);
            $this->setRight($newNode);

            return $newNode;
        }

        return $this;
    }

    /**
     * @param $value
     * @return ?BinaryTreeNode
     */
    public function find($value): ?BinaryTreeNode
    {
        if ($this->nodeValueComparator->equal($this->value, $value)) {
            return $this;
        }

        if ($this->left && $this->nodeValueComparator->lessThan($value, $this->value)) {
            return $this->left->find($value);
        }

        if ($this->right && $this->nodeValueComparator->greaterThan($value, $this->value)) {
            return $this->right->find($value);
        }

        return null;
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value): bool
    {
        return (bool)$this->find($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function remove($value): bool
    {
        $nodeToRemove = $this->find($value);

        if (!$nodeToRemove) {
            throw new Exception('Item not found in the tree');
        }

        $parent = $nodeToRemove->parent;

        if (!$nodeToRemove->left && !$nodeToRemove->right) {
            if ($parent) {
                $parent->removeChild($nodeToRemove);
            } else {
                $nodeToRemove->setValue(null);
            }
        } elseif ($nodeToRemove->left && $nodeToRemove->right) {
            $nextBiggerNode = $nodeToRemove->right->findMin();
            if (!$this->nodeComparator->equal($nextBiggerNode, $nodeToRemove->right)) {
                $this->remove($nextBiggerNode->value);
                $nodeToRemove->setValue($nextBiggerNode->value);
            } else {
                $nodeToRemove->setValue($nodeToRemove->right->value);
                $nodeToRemove->setRight($nodeToRemove->right->right);
            }
        } else {
            $childNode = $nodeToRemove->left ?? $nodeToRemove->right;

            if ($parent) {
                $parent->replaceChild($nodeToRemove, $childNode);
            } else {
                BinaryTreeNode::copyNode($childNode, $nodeToRemove);
            }
        }

        $nodeToRemove->parent = null;

        return true;
    }

    /**
     * @return self
     */
    public function findMin(): self
    {
        if (!$this->left) {
            return $this;
        }

        return $this->left->findMin();
    }
}
