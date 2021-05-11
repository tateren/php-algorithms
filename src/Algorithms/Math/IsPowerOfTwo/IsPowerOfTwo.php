<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\Algorithms\Math\IsPowerOfTwo;

class IsPowerOfTwo
{
    /**
     * @param int $number
     * @return bool
     */
    public static function isPowerOfTwo(int $number): bool
    {
        if ($number < 1) {
            return false;
        }
        $dividedNumber = $number;
        while ($dividedNumber !== 1) {
            if ($dividedNumber % 2 !== 0) {
                return false;
            }
            $dividedNumber /= 2;
        }
        return true;
    }

    /**
     * @param $number
     * @return bool
     */
    public static function isPowerOfTwoBitwise($number): bool
    {
        if ($number < 1) {
            return false;
        }
        return ($number & ($number - 1)) === 0;
    }
}
