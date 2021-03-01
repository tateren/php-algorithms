<?php

use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

it('should compare with default comparator function', function () {
    $comparator = new Comparator();

    expect($comparator->equal(0, 0))->toBe(true);
    expect($comparator->equal(0, 1))->toBe(false);
    expect($comparator->equal('a', 'a'))->toBe(true);
    expect($comparator->lessThan(1, 2))->toBe(true);
    expect($comparator->lessThan(-1, 2))->toBe(true);
    expect($comparator->lessThan('a', 'b'))->toBe(true);
    expect($comparator->lessThan('a', 'ab'))->toBe(true);
    expect($comparator->lessThan(10, 2))->toBe(false);
    expect($comparator->lessThanOrEqual(10, 2))->toBe(false);
    expect($comparator->lessThanOrEqual(1, 1))->toBe(true);
    expect($comparator->lessThanOrEqual(0, 0))->toBe(true);
    expect($comparator->greaterThan(0, 0))->toBe(false);
    expect($comparator->greaterThan(10, 0))->toBe(true);
    expect($comparator->greaterThanOrEqual(10, 0))->toBe(true);
    expect($comparator->greaterThanOrEqual(10, 10))->toBe(true);
    expect($comparator->greaterThanOrEqual(0, 10))->toBe(false);
});

it('should compare with custom comparator function', function () {
    $comparator = new Comparator(function ($a, $b) {
        if (strlen($a) === strlen($b)) {
            return 0;
        }

        return strlen($a) < strlen($b) ? -1 : 1;
    });

    expect($comparator->equal('a', 'b'))->toBe(true);
    expect($comparator->equal('a', ''))->toBe(false);
    expect($comparator->lessThan('b', 'aa'))->toBe(true);
    expect($comparator->greaterThanOrEqual('a', 'aa'))->toBe(false);
    expect($comparator->greaterThanOrEqual('aa', 'a'))->toBe(true);
    expect($comparator->greaterThanOrEqual('a', 'a'))->toBe(true);

    $comparator->reverse();

    expect($comparator->equal('a', 'b'))->toBe(true);
    expect($comparator->equal('a', ''))->toBe(false);
    expect($comparator->lessThan('b', 'aa'))->toBe(false);
    expect($comparator->greaterThanOrEqual('a', 'aa'))->toBe(true);
    expect($comparator->greaterThanOrEqual('aa', 'a'))->toBe(false);
    expect($comparator->greaterThanOrEqual('a', 'a'))->toBe(true);
});
