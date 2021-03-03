<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Stack;

use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedList;
use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedListNode;

class Stack
{
    public LinkedList $linkedList;

    public function __construct()
    {
        $this->linkedList = new LinkedList();
    }

    public function push($value): void
    {
        $this->linkedList->prepend($value);
    }

    public function pop()
    {
        return $this->linkedList->deleteHead()->value ?? null;
    }

    public function toString(callable $callback = null): string
    {
        return $this->linkedList->toString($callback);
    }

    public function peek()
    {
        return $this->linkedList->head->value ?? null;
    }

    public function isEmpty(): bool
    {
        return is_null($this->linkedList->head);
    }

    public function toArray(): array
    {
        return array_map(static function (LinkedListNode $node) {
            return $node->value;
        }, $this->linkedList->toArray());
    }
}
