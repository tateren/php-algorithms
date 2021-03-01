<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\Utils\Comparator;

class Comparator
{
    /**
     * @var callable
     */
    private $compare;

    public function __construct(callable $callback = null)
    {
        $this->compare = $callback ?? fn($a, $b) => $a <=> $b;
    }

    public function equal($a, $b): bool
    {
        return ($this->compare)($a, $b) === 0;
    }

    public function lessThan($a, $b): bool
    {
        return ($this->compare)($a, $b) < 0;
    }

    public function lessThanOrEqual($a, $b): bool
    {
        return $this->lessThan($a, $b) || $this->equal($a, $b);
    }

    public function greaterThan($a, $b): bool
    {
        return ($this->compare)($a, $b) > 0;
    }

    public function greaterThanOrEqual($a, $b): bool
    {
        return $this->greaterThan($a, $b) || $this->equal($a, $b);
    }

    public function reverse(): void
    {
        $compareOriginal = $this->compare;
        $this->compare = fn($a, $b) => $compareOriginal($b, $a);
    }
}
