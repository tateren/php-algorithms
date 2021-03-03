<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\DoublyLinkedList\DoublyLinkedList;

it('should create empty linked list', function () {
    $linkedList = new DoublyLinkedList();
    expect($linkedList->toString())->toBe('');
});

it('should append node to linked list', function () {
    $linkedList = new DoublyLinkedList();

    expect($linkedList->head)->toBeNull();
    expect($linkedList->tail)->toBeNull();

    $linkedList->append(1);
    $linkedList->append(2);

    expect($linkedList->head->next->value)->toBe(2);
    expect($linkedList->tail->previous->value)->toBe(1);
    expect($linkedList->toString())->toBe('1,2');
});

it('should prepend node to linked list', function () {
    $linkedList = new DoublyLinkedList();

    $linkedList->prepend(2);
    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('2');

    $linkedList->append(1);
    $linkedList->prepend(3);

    expect($linkedList->head->next->next->previous)->toBe($linkedList->head->next);
    expect($linkedList->tail->previous->next)->toBe($linkedList->tail);
    expect($linkedList->tail->previous->value)->toBe(2);
    expect($linkedList->toString())->toBe('3,2,1');
});

it('should create linked list from array', function () {
    $linkedList = new DoublyLinkedList();
    $linkedList->fromArray([1, 1, 2, 3, 3, 3, 4, 5]);

    expect($linkedList->toString())->toBe('1,1,2,3,3,3,4,5');
});

it('should delete node by value from linked list', function () {
    $linkedList = new DoublyLinkedList();

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
    expect($linkedList->tail->previous->previous->value)->toBe(2);
    expect($linkedList->toString())->toBe('1,1,2,4,5');

    $linkedList->delete(3);
    expect($linkedList->toString())->toBe('1,1,2,4,5');

    $linkedList->delete(1);
    expect($linkedList->toString())->toBe('2,4,5');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->head->next->next)->toBe($linkedList->tail);
    expect($linkedList->tail->previous->previous)->toBe($linkedList->head);
    expect($linkedList->tail->toString())->toBe('5');

    $linkedList->delete(5);
    expect($linkedList->toString())->toBe('2,4');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('4');

    $linkedList->delete(4);
    expect($linkedList->toString())->toBe('2');

    expect($linkedList->head->toString())->toBe('2');
    expect($linkedList->tail->toString())->toBe('2');
    expect($linkedList->head)->toBe($linkedList->tail);

    $linkedList->delete(2);
    expect($linkedList->toString())->toBe('');
});

it('should delete linked list tail', function () {
    $linkedList = new DoublyLinkedList();

    expect($linkedList->deleteTail())->toBeNull();

    $linkedList->append(1);
    $linkedList->append(2);
    $linkedList->append(3);

    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('3');

    $deletedNode1 = $linkedList->deleteTail();

    assert($deletedNode1 !== null);
    expect($deletedNode1->value)->toBe(3);
    expect($linkedList->toString())->toBe('1,2');
    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('2');

    $deletedNode2 = $linkedList->deleteTail();

    assert($deletedNode2 !== null);
    expect($deletedNode2->value)->toBe(2);
    expect($linkedList->toString())->toBe('1');
    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('1');

    $deletedNode3 = $linkedList->deleteTail();

    assert($deletedNode3 !== null);
    expect($deletedNode3->value)->toBe(1);
    expect($linkedList->toString())->toBe('');
    expect($linkedList->head)->toBeNull();
    expect($linkedList->tail)->toBeNull();
});

it('should delete linked list head', function () {
    $linkedList = new DoublyLinkedList();

    expect($linkedList->deleteHead())->toBeNull();

    $linkedList->append(1);
    $linkedList->append(2);

    expect($linkedList->head->toString())->toBe('1');
    expect($linkedList->tail->toString())->toBe('2');

    $deletedNode1 = $linkedList->deleteHead();

    assert($deletedNode1 !== null);
    expect($deletedNode1->value)->toBe(1);
    expect($linkedList->head->previous)->toBeNull();
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
    $linkedList = new DoublyLinkedList();

    $nodeValue1 = ['value' => 1, 'key' => 'key1'];
    $nodeValue2 = ['value' => 2, 'key' => 'key2'];

    $linkedList
        ->append($nodeValue1)
        ->prepend($nodeValue2);

    $nodeStringifier = function ($value) {
        return "{$value['key']}:{$value['value']}";
    };

    expect($linkedList->toString($nodeStringifier))->toBe('key2:2,key1:1');
});

it('should find node by value', function () {
    $linkedList = new DoublyLinkedList();

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
    $linkedList = new DoublyLinkedList();

    $linkedList
        ->append(['value' => 1, 'key' => 'test1'])
        ->append(['value' => 2, 'key' => 'test2'])
        ->append(['value' => 3, 'key' => 'test3']);

    $node = $linkedList->find(['callback' => function ($value) {
        return $value['key'] === 'test2';
    }]);

    expect($node)->not()->toBeNull();
    assert($node !== null);
    expect($node->value['value'])->toBe(2);
    expect($node->value['key'])->toBe('test2');
    expect($linkedList->find(['callback' => function ($value) {
        return $value['key'] === 'test5';
    }]))->toBeNull();
});

it('should find node by means of custom compare function', function () {
    $comparatorFunction = function ($a, $b) {
        if (!is_array($a) || !is_array($b)) {
            return -1;
        }
        return $a['customValue'] <=> $b['customValue'];
    };

    $linkedList = new DoublyLinkedList($comparatorFunction);

    $linkedList
        ->append(['value' => 1, 'customValue' => 'test1'])
        ->append(['value' => 2, 'customValue' => 'test2'])
        ->append(['value' => 3, 'customValue' => 'test3']);

    $node = $linkedList->find([
        'value' => [
            'value' => 2, 'customValue' => 'test2'
        ]
    ]);

    expect($node)->not()->toBeNull();
    assert($node !== null);
    expect($node->value['value'])->toBe(2);
    expect($node->value['customValue'])->toBe('test2');
    expect($linkedList->find(['value' => 2, 'customValue' => 'test5']))->toBeNull();
});

it('should reverse linked list', function () {
    $linkedList = new DoublyLinkedList();

    // Add test values to linked list->
    $linkedList
        ->append(1)
        ->append(2)
        ->append(3)
        ->append(4);

    expect($linkedList->toString())->toBe('1,2,3,4');
    expect($linkedList->head->value)->toBe(1);
    expect($linkedList->tail->value)->toBe(4);

    // Reverse linked list->
    $linkedList->reverse();

    expect($linkedList->toString())->toBe('4,3,2,1');

    expect($linkedList->head->previous)->toBeNull();
    expect($linkedList->head->value)->toBe(4);
    expect($linkedList->head->next->value)->toBe(3);
    expect($linkedList->head->next->next->value)->toBe(2);
    expect($linkedList->head->next->next->next->value)->toBe(1);

    expect($linkedList->tail->next)->toBeNull();
    expect($linkedList->tail->value)->toBe(1);
    expect($linkedList->tail->previous->value)->toBe(2);
    expect($linkedList->tail->previous->previous->value)->toBe(3);
    expect($linkedList->tail->previous->previous->previous->value)->toBe(4);

    // Reverse linked list back to initial state->
    $linkedList->reverse();

    expect($linkedList->toString())->toBe('1,2,3,4');

    expect($linkedList->head->previous)->toBeNull();
    expect($linkedList->head->value)->toBe(1);
    expect($linkedList->head->next->value)->toBe(2);
    expect($linkedList->head->next->next->value)->toBe(3);
    expect($linkedList->head->next->next->next->value)->toBe(4);

    expect($linkedList->tail->next)->toBeNull();
    expect($linkedList->tail->value)->toBe(4);
    expect($linkedList->tail->previous->value)->toBe(3);
    expect($linkedList->tail->previous->previous->value)->toBe(2);
    expect($linkedList->tail->previous->previous->previous->value)->toBe(1);
});
