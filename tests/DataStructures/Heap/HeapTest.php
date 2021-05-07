<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Heap\Heap;

it('should not allow to create instance of the Heap directly', function () {
    $heap = new Heap();
    $heap->add(5);
})->throws(Error::class);
