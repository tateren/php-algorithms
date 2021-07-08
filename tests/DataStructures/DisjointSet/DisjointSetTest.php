<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\DisjointSet\DisjointSet;

it('should throw error when trying to union and check not existing sets', function () {
    $disjointSet = new DisjointSet();
    $disjointSet->union('A', 'B');
})->throws(Exception::class);

it('should throw error when trying to union and check not existing sets 2', function () {
    $disjointSet = new DisjointSet();
    $disjointSet->inSameSet('A', 'B');
})->throws(Exception::class);

it('should do basic manipulations on disjoint set', function () {
    $disjointSet = new DisjointSet();

    expect($disjointSet->find('A'))->toBeNull();
    expect($disjointSet->find('B'))->toBeNull();

    $disjointSet->makeSet('A');

    expect($disjointSet->find('A'))->toBe('A');
    expect($disjointSet->find('B'))->toBeNull();

    $disjointSet->makeSet('B');

    expect($disjointSet->find('A'))->toBe('A');
    expect($disjointSet->find('B'))->toBe('B');

    $disjointSet->makeSet('C');

    expect($disjointSet->inSameSet('A', 'B'))->toBe(false);

    $disjointSet->union('A', 'B');

    expect($disjointSet->find('A'))->toBe('A');
    expect($disjointSet->find('B'))->toBe('A');
    expect($disjointSet->inSameSet('A', 'B'))->toBe(true);
    expect($disjointSet->inSameSet('B', 'A'))->toBe(true);
    expect($disjointSet->inSameSet('A', 'C'))->toBe(false);

    $disjointSet->union('A', 'A');

    $disjointSet->union('B', 'C');

    expect($disjointSet->find('A'))->toBe('A');
    expect($disjointSet->find('B'))->toBe('A');
    expect($disjointSet->find('C'))->toBe('A');

    expect($disjointSet->inSameSet('A', 'B'))->toBe(true);
    expect($disjointSet->inSameSet('B', 'C'))->toBe(true);
    expect($disjointSet->inSameSet('A', 'C'))->toBe(true);

    $disjointSet
        ->makeSet('E')
        ->makeSet('F')
        ->makeSet('G')
        ->makeSet('H')
        ->makeSet('I');

    $disjointSet
        ->union('E', 'F')
        ->union('F', 'G')
        ->union('G', 'H')
        ->union('H', 'I');

    expect($disjointSet->inSameSet('A', 'I'))->toBe(false);
    expect($disjointSet->inSameSet('E', 'I'))->toBe(true);

    $disjointSet->union('I', 'C');

    expect($disjointSet->find('I'))->toBe('E');
    expect($disjointSet->inSameSet('A', 'I'))->toBe(true);
});

it('should union smaller set with bigger one making bigger one to be new root', function () {
    $disjointSet = new DisjointSet();

    $disjointSet
        ->makeSet('A')
        ->makeSet('B')
        ->makeSet('C')
        ->union('B', 'C')
        ->union('A', 'C');

    expect($disjointSet->find('A'))->toBe('B');
});

it('should do basic manipulations on disjoint set with custom key extractor', function () {
    $keyExtractor = function ($value) {
        return $value['key'];
    };

    $disjointSet = new DisjointSet($keyExtractor);

    $itemA = ['key' => 'A', 'value' => 1];
    $itemB = ['key' => 'B', 'value' => 2];
    $itemC = ['key' => 'C', 'value' => 3];

    expect($disjointSet->find($itemA))->toBeNull();
    expect($disjointSet->find($itemB))->toBeNull();

    $disjointSet->makeSet($itemA);

    expect($disjointSet->find($itemA))->toBe('A');
    expect($disjointSet->find($itemB))->toBeNull();

    $disjointSet->makeSet($itemB);

    expect($disjointSet->find($itemA))->toBe('A');
    expect($disjointSet->find($itemB))->toBe('B');

    $disjointSet->makeSet($itemC);

    expect($disjointSet->inSameSet($itemA, $itemB))->toBe(false);

    $disjointSet->union($itemA, $itemB);

    expect($disjointSet->find($itemA))->toBe('A');
    expect($disjointSet->find($itemB))->toBe('A');
    expect($disjointSet->inSameSet($itemA, $itemB))->toBe(true);
    expect($disjointSet->inSameSet($itemB, $itemA))->toBe(true);
    expect($disjointSet->inSameSet($itemA, $itemC))->toBe(false);

    $disjointSet->union($itemA, $itemC);

    expect($disjointSet->find($itemA))->toBe('A');
    expect($disjointSet->find($itemB))->toBe('A');
    expect($disjointSet->find($itemC))->toBe('A');

    expect($disjointSet->inSameSet($itemA, $itemB))->toBe(true);
    expect($disjointSet->inSameSet($itemB, $itemC))->toBe(true);
    expect($disjointSet->inSameSet($itemA, $itemC))->toBe(true);
});
