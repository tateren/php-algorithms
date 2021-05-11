<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\Algorithms\Math\IsPowerOfTwo\IsPowerOfTwo;

it('should check if the number is made by multiplying twos', function () {
    expect(IsPowerOfTwo::isPowerOfTwo(-1))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(0))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(1))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(2))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(3))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(4))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(5))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(6))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(7))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(8))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(10))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(12))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(16))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(31))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwo(64))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(1024))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwo(1023))->toBe(false);
});

it('should check if the number is made by multiplying twos 2', function () {
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(-1))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(0))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(1))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(2))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(3))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(4))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(5))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(6))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(7))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(8))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(10))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(12))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(16))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(31))->toBe(false);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(64))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(1024))->toBe(true);
    expect(IsPowerOfTwo::isPowerOfTwoBitwise(1023))->toBe(false);
});
