<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\BloomFilter\BloomFilter;

it('should have methods named "insert" and "mayContain"', function () {
    $bloomFilter = new BloomFilter();
    expect(method_exists($bloomFilter, 'insert'))->toBeTrue();
    expect(method_exists($bloomFilter, 'mayContain'))->toBeTrue();
});

it('should create a new filter store with the appropriate methods', function () {
    $bloomFilter = new BloomFilter();
    $store = $bloomFilter->createStore(18);
    expect(method_exists($store, 'getValue'))->toBeTrue();
    expect(method_exists($store, 'setValue'))->toBeTrue();
});

it('should hash deterministically with all 3 hash functions', function () {
    $bloomFilter = new BloomFilter();
    $str1 = 'apple';

    expect($bloomFilter->hash1($str1))->toEqual($bloomFilter->hash1($str1));
    expect($bloomFilter->hash2($str1))->toEqual($bloomFilter->hash2($str1));
    expect($bloomFilter->hash3($str1))->toEqual($bloomFilter->hash3($str1));

    expect($bloomFilter->hash1($str1))->toBe(14);
    expect($bloomFilter->hash2($str1))->toBe(47);
    expect($bloomFilter->hash3($str1))->toBe(10);

    $str2 = 'orange';

    expect($bloomFilter->hash1($str2))->toEqual($bloomFilter->hash1($str2));
    expect($bloomFilter->hash2($str2))->toEqual($bloomFilter->hash2($str2));
    expect($bloomFilter->hash3($str2))->toEqual($bloomFilter->hash3($str2));

    expect($bloomFilter->hash1($str2))->toBe(96);
    expect($bloomFilter->hash2($str2))->toBe(85);
    expect($bloomFilter->hash3($str2))->toBe(86);
});

it('should create an array with 3 hash values', function () {
    $bloomFilter = new BloomFilter();
    expect(count($bloomFilter->getHashValues('abc')))->toBe(3);
    expect($bloomFilter->getHashValues('abc'))->toEqual([66, 63, 54]);
});

it('should insert strings correctly and return true when checking for inserted values', function () {
    $people = [
        'Bruce Wayne',
        'Clark Kent',
        'Barry Allen',
    ];
    $bloomFilter = new BloomFilter();
    foreach ($people as $person) {
        $bloomFilter->insert($person);
    }

    expect($bloomFilter->mayContain('Bruce Wayne'))->toBe(true);
    expect($bloomFilter->mayContain('Clark Kent'))->toBe(true);
    expect($bloomFilter->mayContain('Barry Allen'))->toBe(true);

    expect($bloomFilter->mayContain('Tony Stark'))->toBe(false);
});
