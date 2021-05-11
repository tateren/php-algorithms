<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Tree\SegmentTree\SegmentTree;

it('should build tree for input array #0 with length of power of two', function () {
    $array = [-1, 2];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->segmentTree)->toEqual([-1, -1, 2]);
    expect(count($segmentTree->segmentTree))->toBe((2 * count($array)) - 1);
});

it('should build tree for input array #1 with length of power of two', function () {
    $array = [-1, 2, 4, 0];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->segmentTree)->toEqual([-1, -1, 0, -1, 2, 4, 0]);
    expect(count($segmentTree->segmentTree))->toBe((2 * count($array)) - 1);
});

it('should build tree for input array #0 with length not of power of two', function () {
    $array = [0, 1, 2];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->segmentTree)->toEqual([0, 0, 2, 0, 1, null, null]);
    expect(count($segmentTree->segmentTree))->toBe((2 * 4) - 1);
});

it('should build tree for input array #1 with length not of power of two', function () {
    $array = [-1, 3, 4, 0, 2, 1];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->segmentTree)->toEqual([
        -1, -1, 0, -1, 4, 0, 1, -1, 3, null, null, 0, 2, null, null,
    ]);
    expect(count($segmentTree->segmentTree))->toBe((2 * 8) - 1);
});

it('should build max array', function () {
    $array = [-1, 2, 4, 0];
    $segmentTree = new SegmentTree($array, 'max', -INF);

    expect($segmentTree->segmentTree)->toEqual([4, 2, 4, -1, 2, 4, 0]);
    expect(count($segmentTree->segmentTree))->toBe((2 * count($array)) - 1);
});

it('should build sum array', function () {
    $array = [-1, 2, 4, 0];
    $segmentTree = new SegmentTree($array, function ($a, $b) {
        return $a + $b;
    }, 0);

    expect($segmentTree->segmentTree)->toEqual([5, 1, 4, -1, 2, 4, 0]);
    expect(count($segmentTree->segmentTree))->toBe((2 * count($array)) - 1);
});

it('should do min range query on power of two length array', function () {
    $array = [-1, 3, 4, 0, 2, 1];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->rangeQuery(0, 5))->toBe(-1);
    expect($segmentTree->rangeQuery(0, 2))->toBe(-1);
    expect($segmentTree->rangeQuery(1, 3))->toBe(0);
    expect($segmentTree->rangeQuery(2, 4))->toBe(0);
    expect($segmentTree->rangeQuery(4, 5))->toBe(1);
    expect($segmentTree->rangeQuery(2, 2))->toBe(4);
});

it('should do min range query on not power of two length array', function () {
    $array = [-1, 2, 4, 0];
    $segmentTree = new SegmentTree($array, 'min', INF);

    expect($segmentTree->rangeQuery(0, 4))->toBe(-1);
    expect($segmentTree->rangeQuery(0, 1))->toBe(-1);
    expect($segmentTree->rangeQuery(1, 3))->toBe(0);
    expect($segmentTree->rangeQuery(1, 2))->toBe(2);
    expect($segmentTree->rangeQuery(2, 3))->toBe(0);
    expect($segmentTree->rangeQuery(2, 2))->toBe(4);
});

it('should do max range query', function () {
    $array = [-1, 3, 4, 0, 2, 1];
    $segmentTree = new SegmentTree($array, 'max', -INF);

    expect($segmentTree->rangeQuery(0, 5))->toBe(4);
    expect($segmentTree->rangeQuery(0, 1))->toBe(3);
    expect($segmentTree->rangeQuery(1, 3))->toBe(4);
    expect($segmentTree->rangeQuery(2, 4))->toBe(4);
    expect($segmentTree->rangeQuery(4, 5))->toBe(2);
    expect($segmentTree->rangeQuery(3, 3))->toBe(0);
});

it('should do sum range query', function () {
    $array = [-1, 3, 4, 0, 2, 1];
    $segmentTree = new SegmentTree($array, function ($a, $b) {
        return $a + $b;
    }, 0);

    expect($segmentTree->rangeQuery(0, 5))->toBe(9);
    expect($segmentTree->rangeQuery(0, 1))->toBe(2);
    expect($segmentTree->rangeQuery(1, 3))->toBe(7);
    expect($segmentTree->rangeQuery(2, 4))->toBe(6);
    expect($segmentTree->rangeQuery(4, 5))->toBe(3);
    expect($segmentTree->rangeQuery(3, 3))->toBe(0);
});
