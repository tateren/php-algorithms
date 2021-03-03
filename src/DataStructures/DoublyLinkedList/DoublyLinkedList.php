<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\DoublyLinkedList;

use Tateren\PhpAlgorithms\Utils\Comparator\Comparator;

class DoublyLinkedList
{
    public ?DoublyLinkedListNode $head;

    public ?DoublyLinkedListNode $tail;

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
        return implode(',', array_map(function (DoublyLinkedListNode $node) use ($callback) {
            return $node->toString($callback);
        }, $this->toArray()));
    }

    /**
     * @param $value
     * @return $this
     */
    public function append($value): self
    {
        $node = new DoublyLinkedListNode($value);

        if (is_null($this->head)) {
            $this->head = $node;
            $this->tail = $node;
            return $this;
        }
        $this->tail->next = $node;
        $node->previous = $this->tail;
        $this->tail = $node;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function prepend($value): self
    {
        $node = new DoublyLinkedListNode($value, $this->head);
        if (!is_null($this->head)) {
            $this->head->previous = $node;
        }
        $this->head = $node;

        if (is_null($this->tail)) {
            $this->tail = $node;
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return DoublyLinkedListNode|null
     */
    public function delete($value): ?DoublyLinkedListNode
    {
        if (is_null($this->head)) {
            return null;
        }

        $deletedNode = null;
        $currentNode = $this->head;

        while ($currentNode) {
            if ($this->compare->equal($currentNode->value, $value)) {
                $deletedNode = $currentNode;
                if ($deletedNode === $this->head) {
                    $this->head = $deletedNode->next;
                    if (!is_null($this->head)) {
                        $this->head->previous = null;
                    }
                    if ($deletedNode === $this->tail) {
                        $this->tail = null;
                    }
                } elseif ($deletedNode === $this->tail) {
                    $this->tail = $deletedNode->previous;
                    $this->tail->next = null;
                } else {
                    $previousNode = $deletedNode->previous;
                    $nextNode = $deletedNode->next;

                    $previousNode->next = $nextNode;
                    $nextNode->previous = $previousNode;
                }
            }
            $currentNode = $currentNode->next;
        }
        return $deletedNode;
    }

    public function deleteTail(): ?DoublyLinkedListNode
    {
        if (is_null($this->tail)) {
            return null;
        }

        if ($this->head === $this->tail) {
            $deletedTail = $this->tail;
            $this->head = null;
            $this->tail = null;

            return $deletedTail;
        }

        $deletedTail = $this->tail;

        $this->tail = $this->tail->previous;
        $this->tail->next = null;

        return $deletedTail;
    }

    public function deleteHead(): ?DoublyLinkedListNode
    {
        if (is_null($this->head)) {
            return null;
        }

        $deletedHead = $this->head;

        if ($this->head->next) {
            $this->head = $this->head->next;
            $this->head->previous = null;
        } else {
            $this->head = null;
            $this->tail = null;
        }

        return $deletedHead;
    }

    public function find($findParams): ?DoublyLinkedListNode
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
            $nextNode = $currentNode->next;
            $prevNode = $currentNode->previous;

            $currentNode->next = $prevNode;
            $currentNode->previous = $nextNode;

            $prevNode = $currentNode;
            $currentNode = $nextNode;
        }

        $this->tail = $this->head;
        $this->head = $prevNode;

        return $this;
    }
}
