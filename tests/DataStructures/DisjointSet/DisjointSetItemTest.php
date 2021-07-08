<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\DisjointSet\DisjointSetItem;

it('should do basic manipulation with disjoint set item', function () {
    $itemA = new DisjointSetItem('A');
    $itemB = new DisjointSetItem('B');
    $itemC = new DisjointSetItem('C');
    $itemD = new DisjointSetItem('D');

    expect($itemA->getRank())->toBe(0);
    expect($itemA->getChildren())->toEqual([]);
    expect($itemA->getKey())->toBe('A');
    expect($itemA->getRoot())->toEqual($itemA);
    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(true);

    $itemA->addChild($itemB);
    $itemD->setParent($itemC);

    expect($itemA->getRank())->toBe(1);
    expect($itemC->getRank())->toBe(1);

    expect($itemB->getRank())->toBe(0);
    expect($itemD->getRank())->toBe(0);

    expect(count($itemA->getChildren()))->toBe(1);
    expect(count($itemC->getChildren()))->toBe(1);

    expect($itemA->getChildren()[0])->toEqual($itemB);
    expect($itemC->getChildren()[0])->toEqual($itemD);

    expect(count($itemB->getChildren()))->toBe(0);
    expect(count($itemD->getChildren()))->toBe(0);

    expect($itemA->getRoot())->toEqual($itemA);
    expect($itemB->getRoot())->toEqual($itemA);

    expect($itemC->getRoot())->toEqual($itemC);
    expect($itemD->getRoot())->toEqual($itemC);

    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(false);
    expect($itemC->isRoot())->toBe(true);
    expect($itemD->isRoot())->toBe(false);

    $itemA->addChild($itemC);

    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(false);
    expect($itemC->isRoot())->toBe(false);
    expect($itemD->isRoot())->toBe(false);

    expect($itemA->getRank())->toEqual(3);
    expect($itemB->getRank())->toEqual(0);
    expect($itemC->getRank())->toEqual(1);
});

it('should do basic manipulation with disjoint set item with custom key extractor', function () {
    $keyExtractor = function ($value) {
        return $value['key'];
    };

    $itemA = new DisjointSetItem(['key' => 'A', 'value' => 1], $keyExtractor);
    $itemB = new DisjointSetItem(['key' => 'B', 'value' => 2], $keyExtractor);
    $itemC = new DisjointSetItem(['key' => 'C', 'value' => 3], $keyExtractor);
    $itemD = new DisjointSetItem(['key' => 'D', 'value' => 4], $keyExtractor);
    expect($itemA->getRank())->toBe(0);
    expect($itemA->getChildren())->toEqual([]);
    expect($itemA->getKey())->toBe('A');
    expect($itemA->getRoot())->toEqual($itemA);
    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(true);

    $itemA->addChild($itemB);
    $itemD->setParent($itemC);

    expect($itemA->getRank())->toBe(1);
    expect($itemC->getRank())->toBe(1);

    expect($itemB->getRank())->toBe(0);
    expect($itemD->getRank())->toBe(0);

    expect(count($itemA->getChildren()))->toBe(1);
    expect(count($itemC->getChildren()))->toBe(1);

    expect($itemA->getChildren()[0])->toEqual($itemB);
    expect($itemC->getChildren()[0])->toEqual($itemD);

    expect(count($itemB->getChildren()))->toBe(0);
    expect(count($itemD->getChildren()))->toBe(0);

    expect($itemA->getRoot())->toEqual($itemA);
    expect($itemB->getRoot())->toEqual($itemA);

    expect($itemC->getRoot())->toEqual($itemC);
    expect($itemD->getRoot())->toEqual($itemC);

    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(false);
    expect($itemC->isRoot())->toBe(true);
    expect($itemD->isRoot())->toBe(false);

    $itemA->addChild($itemC);

    expect($itemA->isRoot())->toBe(true);
    expect($itemB->isRoot())->toBe(false);
    expect($itemC->isRoot())->toBe(false);
    expect($itemD->isRoot())->toBe(false);

    expect($itemA->getRank())->toEqual(3);
    expect($itemB->getRank())->toEqual(0);
    expect($itemC->getRank())->toEqual(1);
});
