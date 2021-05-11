<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\AvlTree;

use Exception;
use Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree\BinarySearchTree;
use Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree\SearchBinaryTreeNode;

class AvlTree extends BinarySearchTree
{
    /**
     * @param $value
     * @return mixed
     */
    public function insert($value)
    {
        parent::insert($value);
        $currentNode = $this->root->find($value);
        while ($currentNode) {
            $this->balance($currentNode);
            $currentNode = $currentNode->parent;
        }
        return $currentNode;
    }

    /**
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function remove($value): bool
    {
        $result = parent::remove($value);
        $this->balance($this->root);
        return $result;
    }

    /**
     * @param $node
     */
    public function balance($node): void
    {
        if ($node->balanceFactor() > 1) {
            if ($node->left->balanceFactor() > 0) {
                $this->rotateLeftLeft($node);
            } elseif ($node->left->balanceFactor() < 0) {
                $this->rotateLeftRight($node);
            }
        } elseif ($node->balanceFactor() < -1) {
            if ($node->right->balanceFactor() < 0) {
                $this->rotateRightRight($node);
            } elseif ($node->right->balanceFactor() > 0) {
                $this->rotateRightLeft($node);
            }
        }
    }

    /**
     * @param SearchBinaryTreeNode $rootNode
     */
    public function rotateLeftLeft(SearchBinaryTreeNode $rootNode): void
    {
        $leftNode = $rootNode->left;
        $rootNode->setLeft();

        if ($rootNode->parent) {
            $rootNode->parent->setLeft($leftNode);
        } elseif ($rootNode === $this->root) {
            $this->root = $leftNode; // TODO: fix incompatible types
        }

        if ($leftNode->right) {
            $rootNode->setLeft($leftNode->right);
        }

        $leftNode->setRight($rootNode);
    }

    /**
     * @param SearchBinaryTreeNode $rootNode
     */
    public function rotateLeftRight(SearchBinaryTreeNode $rootNode): void
    {
        $leftNode = $rootNode->left;
        $rootNode->setLeft();

        $leftRightNode = $leftNode->right;
        $leftNode->setRight();

        if ($leftRightNode->left) {
            $leftNode->setRight($leftRightNode->left);
            $leftRightNode->setLeft();
        }

        $rootNode->setLeft($leftRightNode);

        $leftRightNode->setLeft($leftNode);

        $this->rotateLeftLeft($rootNode);
    }

    /**
     * @param SearchBinaryTreeNode $rootNode
     */
    public function rotateRightLeft(SearchBinaryTreeNode $rootNode): void
    {
        $rightNode = $rootNode->right;
        $rootNode->setRight();

        $rightLeftNode = $rightNode->left;
        $rightNode->setLeft();

        if ($rightLeftNode->right) {
            $rightNode->setLeft($rightLeftNode->right);
            $rightLeftNode->setRight();
        }

        $rootNode->setRight($rightLeftNode);

        $rightLeftNode->setRight($rightNode);

        $this->rotateRightRight($rootNode);
    }

    /**
     * @param SearchBinaryTreeNode $rootNode
     */
    public function rotateRightRight(SearchBinaryTreeNode $rootNode): void
    {
        $rightNode = $rootNode->right;
        $rootNode->setRight();

        if ($rootNode->parent) {
            $rootNode->parent->setRight($rightNode);
        } elseif ($rootNode === $this->root) {
            $this->root = $rightNode; // TODO: fix incompatible types
        }

        if ($rightNode->left) {
            $rootNode->setRight($rightNode->left);
        }

        $rightNode->setLeft($rootNode);
    }
}
