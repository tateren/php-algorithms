<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\LinkedList;

class LinkedListNode
{
    /**
     * @var mixed
     */
    public $value;
    public ?LinkedListNode $next;

    public function __construct($value, LinkedListNode $next = null)
    {
        $this->value = $value;
        $this->next = $next;
    }

    public function toString(callable $callback = null): string
    {
        return $callback ? $callback($this->value) : (string)$this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
