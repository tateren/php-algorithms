<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Tree\BinarySearchTree\BinarySearchTree;

it('should create binary search tree', function () {
    $bst = new BinarySearchTree();

    expect($bst)->not()->toBeNull();
    expect($bst->root)->not()->toBeNull();
    expect($bst->root->value)->toBeNull();
    expect($bst->root->left)->toBeNull();
    expect($bst->root->right)->toBeNull();
});

it('should insert values', function () {
    $bst = new BinarySearchTree();

    $insertedNode1 = $bst->insert(10);
    $insertedNode2 = $bst->insert(20);
    $bst->insert(5);

    expect($bst->toString())->toBe('5,10,20');
    expect($insertedNode1->value)->toBe(10);
    expect($insertedNode2->value)->toBe(20);
});

it('should check if value exists', function () {
    $bst = new BinarySearchTree();

    $bst->insert(10);
    $bst->insert(20);
    $bst->insert(5);

    expect($bst->contains(20))->toBe(true);
    expect($bst->contains(40))->toBe(false);
});

it('should remove nodes', function () {
    $bst = new BinarySearchTree();

    $bst->insert(10);
    $bst->insert(20);
    $bst->insert(5);

    expect($bst->toString())->toBe('5,10,20');

    $removed1 = $bst->remove(5);
    expect($bst->toString())->toBe('10,20');
    expect($removed1)->toBe(true);

    $removed2 = $bst->remove(20);
    expect($bst->toString())->toBe('10');
    expect($removed2)->toBe(true);
});

it('should insert object values', function () {
    $nodeValueCompareFunction = function ($a, $b) {
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

    $bst = new BinarySearchTree($nodeValueCompareFunction);

    $bst->insert($obj2);
    $bst->insert($obj3);
    $bst->insert($obj1);

    expect($bst->toString())->toBe('obj1,obj2,obj3');
});

it('should be traversed to sorted array', function () {
    $bst = new BinarySearchTree();

    $bst->insert(10);
    $bst->insert(-10);
    $bst->insert(20);
    $bst->insert(-20);
    $bst->insert(25);
    $bst->insert(6);

    expect($bst->toString())->toBe('-20,-10,6,10,20,25');
    expect($bst->root->height())->toBe(2);

    $bst->insert(4);

    expect($bst->toString())->toBe('-20,-10,4,6,10,20,25');
    expect($bst->root->height())->toBe(3);
});
