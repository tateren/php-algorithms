<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\LinkedList;

use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class LinkedList
{
    public ?LinkedListNode $head;

    public ?LinkedListNode $tail;

    /**
     * @var ?callable
     */
    public $compare;

    public function __construct(callable $comparatorFunction = null)
    {
        $this->head = null;
        $this->tail = null;
        $this->compare = new Comparator($comparatorFunction);
    }

    public function toString(callable $callback = null): string
    {
        return implode(',', array_map(function (LinkedListNode $node) use ($callback) {
            return $node->toString($callback);
        }, $this->toArray()));
    }

    /**
     * @param $value
     * @return $this
     */
    public function append($value): self
    {
        $node = new LinkedListNode($value);
        if (is_null($this->head)) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $this->tail->next = $node;
            $this->tail = $node;
        }
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function prepend($value): self
    {
        $node = new LinkedListNode($value);
        $node->next = $this->head;
        $this->head = $node;
        if (is_null($this->tail)) {
            $this->tail = $node;
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @return LinkedListNode|null
     */
    public function delete($value): ?LinkedListNode
    {
        if (is_null($this->head)) {
            return null;
        }

        $deletedNode = null;

        while ($this->head && $this->compare->equal($this->head->value, $value)) {
            $deletedNode = $this->head;
            $this->head = $this->head->next;
        }

        $currentNode = $this->head;

        if (!is_null($currentNode)) {
            while ($currentNode->next) {
                if ($this->compare->equal($currentNode->next->value, $value)) {
                    $deletedNode = $currentNode->next;
                    $currentNode->next = $currentNode->next->next;
                } else {
                    $currentNode = $currentNode->next;
                }
            }
        }

        if ($this->compare->equal($this->tail->value, $value)) {
            $this->tail = $currentNode;
        }

        return $deletedNode;
    }

    public function deleteTail(): LinkedListNode
    {
        $deletedTail = $this->tail;
        if ($this->head === $this->tail) {
            $this->head = null;
            $this->tail = null;
            return $deletedTail;
        }

        $currentNode = $this->head;

        while ($currentNode->next) {
            if (is_null($currentNode->next->next)) {
                $currentNode->next = null;
            } else {
                $currentNode = $currentNode->next;
            }
        }

        $this->tail = $currentNode;

        return $deletedTail;
    }

    public function deleteHead(): ?LinkedListNode
    {
        if (is_null($this->head)) {
            return null;
        }

        $deletedHead = $this->head;

        if ($this->head->next) {
            $this->head = $this->head->next;
        } else {
            $this->head = null;
            $this->tail = null;
        }

        return $deletedHead;
    }

    public function find($findParams): ?LinkedListNode
    {
        $callback = $findParams['callback'] ?? null;
        $value = $findParams['value'] ?? null;

        if (is_null($this->head)) {
            return null;
        }

        $currentNode = $this->head;
        while ($currentNode) {
            if ($callback && $callback($currentNode->value)) {
                return $currentNode;
            }
            if (!is_null($value) && $this->compare->equal($currentNode->value, $value)) {
                return $currentNode;
            }
            $currentNode = $currentNode->next;
        }
        return null;
    }

    public function fromArray(array $array): self
    {
        foreach ($array as $value) {
            $this->append($value);
        }
        return $this;
    }

    public function toArray(): array
    {
        $nodes = [];
        $currentNode = $this->head;
        while ($currentNode) {
            $nodes[] = $currentNode;
            $currentNode = $currentNode->next;
        }
        return $nodes;
    }

    public function reverse(): self
    {
        $currentNode = $this->head;
        $prevNode = null;
        $nextNode = null;

        while ($currentNode !== null) {
            // Store next node.
            $nextNode = $currentNode->next;

            // Change next node of the current node so it would link to previous node.
            $currentNode->next = $prevNode;

            // Move prevNode and currNode nodes one step forward.
            $prevNode = $currentNode;
            $currentNode = $nextNode;
        }

        // Reset head and tail.
        $this->tail = $this->head;
        $this->head = $prevNode;

        return $this;
    }
}
