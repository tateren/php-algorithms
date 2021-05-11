<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Tree\RedBlackTree;

use Exception;
use Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree\BinarySearchTree;

const RED_BLACK_TREE_COLORS = [
    'red' => 'red',
    'black' => 'black',
];

const COLOR_PROP_NAME = 'color';

class RedBlackTree extends BinarySearchTree
{
    public function insert($value)
    {
        $insertedNode = parent::insert($value);

        if ($this->nodeComparator->equal($insertedNode, $this->root)) {
            $this->makeNodeBlack($insertedNode);
        } else {
            $this->makeNodeRed($insertedNode);
        }

        $this->balance($insertedNode);

        return $insertedNode;
    }

    public function remove($value): bool
    {
        throw new Exception("Can't remove $value . Remove method is not implemented yet");
    }

    public function balance($node): void
    {
        if ($this->nodeComparator->equal($node, $this->root)) {
            return;
        }

        if ($this->isNodeBlack($node->parent)) {
            return;
        }

        $grandParent = $node->parent->parent;

        if ($node->uncle() && $this->isNodeRed($node->uncle())) {
            $this->makeNodeBlack($node->uncle());
            $this->makeNodeBlack($node->parent);

            if (!$this->nodeComparator->equal($grandParent, $this->root)) {
                $this->makeNodeRed($grandParent);
            } else {
                return;
            }

            $this->balance($grandParent);
        } elseif (!$node->uncle() || $this->isNodeBlack($node->uncle())) {
            if ($grandParent) {
                if ($this->nodeComparator->equal($grandParent->left, $node->parent)) {
                    if ($this->nodeComparator->equal($node->parent->left, $node)) {
                        $newGrandParent = $this->leftLeftRotation($grandParent);
                    } else {
                        $newGrandParent = $this->leftRightRotation($grandParent);
                    }
                } elseif ($this->nodeComparator->equal($node->parent->right, $node)) {
                    $newGrandParent = $this->rightRightRotation($grandParent);
                } else {
                    $newGrandParent = $this->rightLeftRotation($grandParent);
                }

                if ($newGrandParent && $newGrandParent->parent === null) {
                    $this->root = $newGrandParent;

                    $this->makeNodeBlack($this->root);
                }

                $this->balance($newGrandParent);
            }
        }
    }

    public function leftLeftRotation($grandParentNode)
    {
        $grandGrandParent = $grandParentNode->parent;

        $grandParentNodeIsLeft = null;
        if ($grandGrandParent) {
            $grandParentNodeIsLeft = $this->nodeComparator->equal($grandGrandParent->left, $grandParentNode);
        }

        $parentNode = $grandParentNode->left;

        $parentRightNode = $parentNode->right;

        $parentNode->setRight($grandParentNode);

        $grandParentNode->setLeft($parentRightNode);

        if ($grandGrandParent) {
            if ($grandParentNodeIsLeft) {
                $grandGrandParent->setLeft($parentNode);
            } else {
                $grandGrandParent->setRight($parentNode);
            }
        } else {
            $parentNode->parent = null;
        }

        $this->swapNodeColors($parentNode, $grandParentNode);

        return $parentNode;
    }

    public function leftRightRotation($grandParentNode)
    {
        $parentNode = $grandParentNode->left;
        $childNode = $parentNode->right;

        $childLeftNode = $childNode->left;

        $childNode->setLeft($parentNode);

        $parentNode->setRight($childLeftNode);

        $grandParentNode->setLeft($childNode);

        return $this->leftLeftRotation($grandParentNode);
    }

    public function rightRightRotation($grandParentNode)
    {
        $grandGrandParent = $grandParentNode->parent;

        if ($grandGrandParent) {
            $grandParentNodeIsLeft = $this->nodeComparator->equal($grandGrandParent->left, $grandParentNode);
        }

        $parentNode = $grandParentNode->right;

        $parentLeftNode = $parentNode->left;

        $parentNode->setLeft($grandParentNode);

        $grandParentNode->setRight($parentLeftNode);

        if ($grandGrandParent) {
            if ($grandParentNodeIsLeft) {
                $grandGrandParent->setLeft($parentNode);
            } else {
                $grandGrandParent->setRight($parentNode);
            }
        } else {
            $parentNode->parent = null;
        }

        $this->swapNodeColors($parentNode, $grandParentNode);

        return $parentNode;
    }

    public function rightLeftRotation($grandParentNode)
    {
        $parentNode = $grandParentNode->right;
        $childNode = $parentNode->left;

        $childRightNode = $childNode->right;

        $childNode->setRight($parentNode);

        $parentNode->setLeft($childRightNode);

        $grandParentNode->setRight($childNode);

        return $this->rightRightRotation($grandParentNode);
    }

    public function makeNodeRed($node)
    {
        $node->meta->set(COLOR_PROP_NAME, RED_BLACK_TREE_COLORS['red']);
        return $node;
    }

    public function makeNodeBlack($node)
    {
        $node->meta->set(COLOR_PROP_NAME, RED_BLACK_TREE_COLORS['black']);
        return $node;
    }

    public function isNodeRed($node): bool
    {
        return $node->meta->get(COLOR_PROP_NAME) === RED_BLACK_TREE_COLORS['red'];
    }

    public function isNodeBlack($node): bool
    {
        return $node->meta->get(COLOR_PROP_NAME) === RED_BLACK_TREE_COLORS['black'];
    }

    public function isNodeColored($node): bool
    {
        return $this->isNodeRed($node) || $this->isNodeBlack($node);
    }

    public function swapNodeColors($firstNode, $secondNode): void
    {
        $firstColor = $firstNode->meta->get(COLOR_PROP_NAME);
        $secondColor = $secondNode->meta->get(COLOR_PROP_NAME);

        $firstNode->meta->set(COLOR_PROP_NAME, $secondColor);
        $secondNode->meta->set(COLOR_PROP_NAME, $firstColor);
    }
}
