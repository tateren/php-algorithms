<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedList;

it('should create empty linked list', function () {
    $linkedList = new LinkedList();
    expect($linkedList->toString())->toBe('');
});

it('should append node to linked list', function () {
    $linkedList = new LinkedList();

    expect($linkedList->head)->toBeNull();
    expect($linkedList->tail)->toBeNull();

    $linkedList->append(1);
    $linkedList->append(2);

    expect($linkedList->toString())->toBe('1,2');
    expect($linkedList->tail->next)->toBeNull();
});

it('should prepend node to linked list', function () {
    $linkedList = new LinkedList();

    $linkedList->prepend(2);
    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('2');

    $linkedList->append(1);
    $linkedList->prepend(3);

    expect($linkedList->toString())->toBe('3,2,1');
});

it('should delete node by value from linked list', function () {
    $linkedList = new LinkedList();

    expect($linkedList->delete(5))->toBeNull();

    $linkedList->append(1);
    $linkedList->append(1);
    $linkedList->append(2);
    $linkedList->append(3);
    $linkedList->append(3);
    $linkedList->append(3);
    $linkedList->append(4);
    $linkedList->append(5);

    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('5');

    $deletedNode = $linkedList->delete(3);
    assert($deletedNode !== null);
    expect($deletedNode->value)->toBe(3);
    expect($linkedList->toString())->toBe('1,1,2,4,5');

    $linkedList->delete(3);
    expect($linkedList->toString())->toBe('1,1,2,4,5');

    $linkedList->delete(1);
    expect($linkedList->toString())->toBe('2,4,5');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('5');

    $linkedList->delete(5);
    expect($linkedList->toString())->toBe('2,4');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('4');

    $linkedList->delete(4);
    expect($linkedList->toString())->toBe('2');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('2');

    $linkedList->delete(2);
    expect($linkedList->toString())->toBe('');
});

it('should delete linked list tail', function () {
    $linkedList = new LinkedList();

    $linkedList->append(1);
    $linkedList->append(2);
    $linkedList->append(3);

    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('3');

    $deletedNode1 = $linkedList->deleteTail();

    expect($deletedNode1->value)->toBe(3);
    expect($linkedList->toString())->toBe('1,2');
    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('2');

    $deletedNode2 = $linkedList->deleteTail();

    expect($deletedNode2->value)->toBe(2);
    expect($linkedList->toString())->toBe('1');
    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('1');

    $deletedNode3 = $linkedList->deleteTail();

    expect($deletedNode3->value)->toBe(1);
    expect($linkedList->toString())->toBe('');
    expect($linkedList->head)->toBeNull();
    expect($linkedList->tail)->toBeNull();
});

it('should delete linked list head', function () {
    $linkedList = new LinkedList();

    expect($linkedList->deleteHead())->toBeNull();

    $linkedList->append(1);
    $linkedList->append(2);

    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('2');

    $deletedNode1 = $linkedList->deleteHead();

    assert($deletedNode1 !== null);
    expect($deletedNode1->value)->toBe(1);
    expect($linkedList->toString())->toBe('2');
    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('2');

    $deletedNode2 = $linkedList->deleteHead();

    assert($deletedNode2 !== null);
    expect($deletedNode2->value)->toBe(2);
    expect($linkedList->toString())->toBe('');
    expect($linkedList->head)->toBeNull();
    expect($linkedList->tail)->toBeNull();
});

it('should be possible to store objects in the list and to print them out', function () {
    $linkedList = new LinkedList();

    $nodeValue1 = ['value' => 1, 'key' => 'key1'];
    $nodeValue2 = ['value' => 2, 'key' => 'key2'];

    $linkedList
        ->append($nodeValue1)
        ->prepend($nodeValue2);

    $nodeStringifier = function ($value) {
        return "{$value[ 'key' ]}:{$value[ 'value' ]}";
    };

    expect($linkedList->toString($nodeStringifier))->toBe('key2:2,key1:1');
});

it('should find node by value', function () {
    $linkedList = new LinkedList();

    expect($linkedList->find(['value' => 5]))->toBeNull();

    $linkedList->append(1);
    expect($linkedList->find(['value' => 1]))->not()->toBeNull();

    $linkedList
        ->append(2)
        ->append(3);

    $node = $linkedList->find(['value' => 2]);

    assert($node !== null);
    expect($node->value)->toBe(2);
    expect($linkedList->find(['value' => 5]))->toBeNull();
});

it('should find node by callback', function () {
    $linkedList = new LinkedList();

    $linkedList
        ->append(['value' => 1, 'key' => 'test1'])
        ->append(['value' => 2, 'key' => 'test2'])
        ->append(['value' => 3, 'key' => 'test3']);

    $node = $linkedList->find(['callback' => function ($value) {
        return $value['key'] === 'test2';
    }]);

    assert($node !== null);
    expect($node)->not()->toBeNull();
    expect($node->value['value'])->toBe(2);
    expect($node->value['key'])->toBe('test2');
    expect($linkedList->find(['callback' => function ($value) {
        return $value['key'] === 'test5';
    }]))->toBeNull();
});

it('should create linked list from array', function () {
    $linkedList = new LinkedList();
    $linkedList->fromArray([1, 1, 2, 3, 3, 3, 4, 5]);

    expect($linkedList->toString())->toBe('1,1,2,3,3,3,4,5');
});

it('should find node by means of custom compare function', function () {
    $comparatorFunction = function ($a, $b) {
        if (!is_array($a) || !is_array($b)) {
            return -1;
        }

        if ($a['customValue'] === $b['customValue']) {
            return 0;
        }

        return ($a['customValue'] < $b['customValue']) ? -1 : 1;
    };

    $linkedList = new LinkedList($comparatorFunction);

    $linkedList
        ->append(['value' => 1, 'customValue' => 'test1'])
        ->append(['value' => 2, 'customValue' => 'test2'])
        ->append(['value' => 3, 'customValue' => 'test3']);

    $node = $linkedList->find([
        'value' => ['value' => 2, 'customValue' => 'test2']
    ]);

    assert($node !== null);
    expect($node)->not()->toBeNull();
    expect($node->value['value'])->toBe(2);
    expect($node->value['customValue'])->toBe('test2');
    expect($linkedList->find(['value' => 2, 'customValue' => 'test5']))->toBeNull();
});

it('should find preferring callback over compare function', function () {
    $greaterThan = function ($value, $compareTo) {
        return ($value > $compareTo ? 0 : 1);
    };

    $linkedList = new LinkedList($greaterThan);
    $linkedList->fromArray([1, 2, 3, 4, 5]);

    $node = $linkedList->find(['value' => 3]);
    assert($node !== null);
    expect($node->value)->toBe(4);

    $node = $linkedList->find(['callback' => function ($value) {
        return $value < 3;
    }]);
    expect($node->value)->toBe(1);
});

it('should convert to array', function () {
    $linkedList = new LinkedList();
    $linkedList->append(1);
    $linkedList->append(2);
    $linkedList->append(3);
    expect(implode(',', $linkedList->toArray()))->toBe('1,2,3');
});

it('should reverse linked list', function () {
    $linkedList = new LinkedList();

    // Add test values to linked list.
    $linkedList->append(1)->append(2)->append(3);

    expect($linkedList->toString())->toBe('1,2,3');
    expect($linkedList->head->value)->toBe(1);
    expect($linkedList->tail->value)->toBe(3);

    // Reverse linked list.
    $linkedList->reverse();
    expect($linkedList->toString())->toBe('3,2,1');
    expect($linkedList->head->value)->toBe(3);
    expect($linkedList->tail->value)->toBe(1);

    // Reverse linked list back to initial state.
    $linkedList->reverse();
    expect($linkedList->toString())->toBe('1,2,3');
    expect($linkedList->head->value)->toBe(1);
    expect($linkedList->tail->value)->toBe(3);
});
