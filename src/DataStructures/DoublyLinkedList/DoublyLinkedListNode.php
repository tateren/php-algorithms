<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\DoublyLinkedList;

class DoublyLinkedListNode
{
    /**
     * @var mixed
     */
    public $value;

    public ?DoublyLinkedListNode $next;
    public ?DoublyLinkedListNode $previous;

    public function __construct($value, DoublyLinkedListNode $next = null, DoublyLinkedListNode $previous = null)
    {
        $this->value = $value;
        $this->next = $next;
        $this->previous = $previous;
    }

    /**
     * @param ?callable $callback
     * @return string
     */
    public function toString(callable $callback = null): string
    {
        return $callback ? $callback($this->value) : (string)$this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
