<?php

use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedListNode;

it('should create list node with value', function () {
    $node = new LinkedListNode(1);

    expect($node->value)->toBe(1);
    expect($node->next)->toBeNull();
});

it('should create list node with object as a value', function () {
    $nodeValue = ['value' => 1, 'key' => 'test'];
    $node = new LinkedListNode($nodeValue);

    expect($node->value['value'])->toBe(1);
    expect($node->value['key'])->toBe('test');
    expect($node->next)->toBeNull();
});

it('should link nodes together', function () {
    $node2 = new LinkedListNode(2);
    $node1 = new LinkedListNode(1, $node2);

    expect($node1->next)->not->toBeNull();
    expect($node2->next)->toBeNull();
    expect($node1->value)->toBe(1);
    expect($node1->next->value)->toBe(2);
});

it('should convert node to string', function () {
    $node = new LinkedListNode(1);

    expect($node->toString())->toBe('1');

    $node->value = 'string value';
    expect($node->toString())->toBe('string value');
});

it('should convert node to string with custom stringifier', function () {
    $nodeValue = ['value' => 1, 'key' => 'test'];
    $node = new LinkedListNode($nodeValue);
    $toStringCallback = function ($value) {
        return "value: {$value['value']}, key: {$value['key']}";
    };

    expect($node->toString($toStringCallback))->toBe('value: 1, key: test');
});
