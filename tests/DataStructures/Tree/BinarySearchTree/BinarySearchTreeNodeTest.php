<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree\SearchBinaryTreeNode;

it('should create binary search tree', function () {
    $bstNode = new SearchBinaryTreeNode(2);

    expect($bstNode->value)->toBe(2);
    expect($bstNode->left)->toBeNull();
    expect($bstNode->right)->toBeNull();
});

it('should insert in itself if it is empty', function () {
    $bstNode = new SearchBinaryTreeNode();
    $bstNode->insert(1);

    expect($bstNode->value)->toBe(1);
    expect($bstNode->left)->toBeNull();
    expect($bstNode->right)->toBeNull();
});

it('should insert nodes in correct order', function () {
    $bstNode = new SearchBinaryTreeNode(2);
    $insertedNode1 = $bstNode->insert(1);

    expect($insertedNode1->value)->toBe(1);
    expect($bstNode->toString())->toBe('1,2');
    expect($bstNode->contains(1))->toBe(true);
    expect($bstNode->contains(3))->toBe(false);

    $insertedNode2 = $bstNode->insert(3);

    expect($insertedNode2->value)->toBe(3);
    expect($bstNode->toString())->toBe('1,2,3');
    expect($bstNode->contains(3))->toBe(true);
    expect($bstNode->contains(4))->toBe(false);

    $bstNode->insert(7);

    expect($bstNode->toString())->toBe('1,2,3,7');
    expect($bstNode->contains(7))->toBe(true);
    expect($bstNode->contains(8))->toBe(false);

    $bstNode->insert(4);

    expect($bstNode->toString())->toBe('1,2,3,4,7');
    expect($bstNode->contains(4))->toBe(true);
    expect($bstNode->contains(8))->toBe(false);

    $bstNode->insert(6);

    expect($bstNode->toString())->toBe('1,2,3,4,6,7');
    expect($bstNode->contains(6))->toBe(true);
    expect($bstNode->contains(8))->toBe(false);
});

it('should not insert duplicates', function () {
    $bstNode = new SearchBinaryTreeNode(2);
    $bstNode->insert(1);

    expect($bstNode->toString())->toBe('1,2');
    expect($bstNode->contains(1))->toBe(true);
    expect($bstNode->contains(3))->toBe(false);

    $bstNode->insert(1);

    expect($bstNode->toString())->toBe('1,2');
    expect($bstNode->contains(1))->toBe(true);
    expect($bstNode->contains(3))->toBe(false);
});

it('should find min node', function () {
    $node = new SearchBinaryTreeNode(10);

    $node->insert(20);
    $node->insert(30);
    $node->insert(5);
    $node->insert(40);
    $node->insert(1);

    expect($node->findMin())->not->toBeNull();
    expect($node->findMin()->value)->toBe(1);
});

it('should be possible to attach meta information to binary search tree nodes', function () {
    $node = new SearchBinaryTreeNode(10);

    $node->insert(20);
    $node1 = $node->insert(30);
    $node->insert(5);
    $node->insert(40);
    $node2 = $node->insert(1);

    $node->meta->set('color', 'red');
    $node1->meta->set('color', 'black');
    $node2->meta->set('color', 'white');

    expect($node->meta->get('color'))->toBe('red');

    expect($node->findMin())->not->toBeNull();
    expect($node->findMin()->value)->toBe(1);
    expect($node->findMin()->meta->get('color'))->toBe('white');
    expect($node->find(30)->meta->get('color'))->toBe('black');
});

it('should find node', function () {
    $node = new SearchBinaryTreeNode(10);

    $node->insert(20);
    $node->insert(30);
    $node->insert(5);
    $node->insert(40);
    $node->insert(1);

    expect($node->find(6))->toBeNull();
    expect($node->find(5))->not->toBeNull();
    expect($node->find(5)->value)->toBe(5);
});

it('should remove leaf nodes', function () {
    $bstRootNode = new SearchBinaryTreeNode();

    $bstRootNode->insert(10);
    $bstRootNode->insert(20);
    $bstRootNode->insert(5);

    expect($bstRootNode->toString())->toBe('5,10,20');

    $removed1 = $bstRootNode->remove(5);
    expect($bstRootNode->toString())->toBe('10,20');
    expect($removed1)->toBe(true);

    $removed2 = $bstRootNode->remove(20);
    expect($bstRootNode->toString())->toBe('10');
    expect($removed2)->toBe(true);
});

it('should remove nodes with one child', function () {
    $bstRootNode = new SearchBinaryTreeNode();

    $bstRootNode->insert(10);
    $bstRootNode->insert(20);
    $bstRootNode->insert(5);
    $bstRootNode->insert(30);

    expect($bstRootNode->toString())->toBe('5,10,20,30');

    $bstRootNode->remove(20);
    expect($bstRootNode->toString())->toBe('5,10,30');

    $bstRootNode->insert(1);
    expect($bstRootNode->toString())->toBe('1,5,10,30');

    $bstRootNode->remove(5);
    expect($bstRootNode->toString())->toBe('1,10,30');
});

it('should remove nodes with two children', function () {
    $bstRootNode = new SearchBinaryTreeNode();

    $bstRootNode->insert(10);
    $bstRootNode->insert(20);
    $bstRootNode->insert(5);
    $bstRootNode->insert(30);
    $bstRootNode->insert(15);
    $bstRootNode->insert(25);

    expect($bstRootNode->toString())->toBe('5,10,15,20,25,30');
    expect($bstRootNode->find(20)->left->value)->toBe(15);
    expect($bstRootNode->find(20)->right->value)->toBe(30);

    $bstRootNode->remove(20);
    expect($bstRootNode->toString())->toBe('5,10,15,25,30');

    $bstRootNode->remove(15);
    expect($bstRootNode->toString())->toBe('5,10,25,30');

    $bstRootNode->remove(10);
    expect($bstRootNode->toString())->toBe('5,25,30');
    expect($bstRootNode->value)->toBe(25);

    $bstRootNode->remove(25);
    expect($bstRootNode->toString())->toBe('5,30');

    $bstRootNode->remove(5);
    expect($bstRootNode->toString())->toBe('30');
});

it('should remove node with no parent', function () {
    $bstRootNode = new SearchBinaryTreeNode();
    expect($bstRootNode->toString())->toBe('');

    $bstRootNode->insert(1);
    $bstRootNode->insert(2);
    expect($bstRootNode->toString())->toBe('1,2');

    $bstRootNode->remove(1);
    expect($bstRootNode->toString())->toBe('2');

    $bstRootNode->remove(2);
    expect($bstRootNode->toString())->toBe('');
});

it('should throw error when trying to remove not existing node', function () {
    $bstRootNode = new SearchBinaryTreeNode();

    $bstRootNode->insert(10);
    $bstRootNode->insert(20);
    $bstRootNode->remove(30);
})->throws(Exception::class);

it('should be possible to use objects as node values', function () {
    $nodeValueComparatorCallback = function ($a, $b) {
        $normalizedA = $a ?? new class {
            public ?int $value = null;
        };
        $normalizedB = $b ?? new class {
            public ?int $value = null;
        };
        return $normalizedA->value <=> $normalizedB->value;
    };

    $obj1 = new class {
        public string $key = 'obj1';
        public int $value = 1;

        public function __toString()
        {
            return 'obj1';
        }
    };
    $obj2 = new class {
        public string $key = 'obj2';
        public int $value = 2;

        public function __toString()
        {
            return 'obj2';
        }
    };
    $obj3 = new class {
        public string $key = 'obj3';
        public int $value = 3;

        public function __toString()
        {
            return 'obj3';
        }
    };
    $bstNode = new SearchBinaryTreeNode($obj2, $nodeValueComparatorCallback);
    $bstNode->insert($obj1);

    expect($bstNode->toString())->toBe('obj1,obj2');
    expect($bstNode->contains($obj1))->toBe(true);
    expect($bstNode->contains($obj3))->toBe(false);

    $bstNode->insert($obj3);

    expect($bstNode->toString())->toBe('obj1,obj2,obj3');
    expect($bstNode->contains($obj3))->toBe(true);

    expect($bstNode->findMin()->value)->toEqual($obj1);
});

it('should abandon removed node', function () {
    $rootNode = new SearchBinaryTreeNode('foo');
    $rootNode->insert('bar');
    $childNode = $rootNode->find('bar');
    $rootNode->remove('bar');

    expect($childNode->parent)->toBeNull();
});
