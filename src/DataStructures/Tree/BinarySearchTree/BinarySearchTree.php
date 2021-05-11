<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree;

use Exception;
use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class BinarySearchTree
{
    public SearchBinaryTreeNode $root;
    public Comparator $nodeComparator;

    /**
     * @param callable|null $nodeValueCompareFunction
     */
    public function __construct(callable $nodeValueCompareFunction = null)
    {
        $this->root = new SearchBinaryTreeNode(null, $nodeValueCompareFunction);
        $this->nodeComparator = $this->root->nodeComparator;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function insert($value)
    {
        return $this->root->insert($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function contains($value): bool
    {
        return $this->root->contains($value);
    }

    /**
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function remove($value): bool
    {
        return $this->root->remove($value);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->root->toString();
    }
}
