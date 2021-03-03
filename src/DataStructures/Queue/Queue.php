<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Queue;

use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedList;

class Queue
{
    public LinkedList $linkedList;

    public function __construct()
    {
        $this->linkedList = new LinkedList();
    }

    public function enqueue($value): void
    {
        $this->linkedList->append($value);
    }

    public function dequeue()
    {
        return $this->linkedList->deleteHead()->value ?? null;
    }

    public function peek()
    {
        return $this->linkedList->head->value ?? null;
    }

    public function isEmpty(): bool
    {
        return is_null($this->linkedList->head);
    }

    public function toString(callable $callback = null): string
    {
        return $this->linkedList->toString($callback);
    }
}
