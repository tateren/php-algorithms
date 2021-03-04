<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\HashTable;

use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedList;
use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedListNode;

class HashTable
{
    /**
     * @var LinkedList[]
     */
    public array $buckets = [];
    private array $keys;

    public function __construct($size = 32)
    {
        for ($i = 0; $i < $size; $i++) {
            $this->buckets[$i] = new LinkedList();
        }
        $this->keys = [];
    }

    public function hash(string $key): int
    {
        $hash = array_reduce(str_split($key), function (int $hashAccumulator, string $keySymbol) {
            return $hashAccumulator + ord($keySymbol);
        }, 0);
        return $hash % count($this->buckets);
    }

    public function set(string $key, $value): void
    {
        $keyHash = $this->hash($key);
        $this->keys[$key] = $keyHash;
        $bucketLinkedList = $this->buckets[$keyHash];
        $node = $bucketLinkedList->find(['callback' => function ($nodeValue) use ($key) {
            return $nodeValue['key'] === $key;
        }]);

        if (is_null($node)) {
            $bucketLinkedList->append(['key' => $key, 'value' => $value]);
        } else {
            $node->value['value'] = $value;
        }
    }

    public function delete(string $key): ?LinkedListNode
    {
        $keyHash = $this->hash($key);
        unset($this->keys[$key]);
        $bucketLinkedList = $this->buckets[$keyHash];
        $node = $bucketLinkedList->find(['callback' => function ($nodeValue) use ($key) {
            return $nodeValue['key'] === $key;
        }]);

        if ($node) {
            return $bucketLinkedList->delete($node->value);
        }

        return null;
    }

    public function get(string $key)
    {
        $bucketLinkedList = $this->buckets[$this->hash($key)];
        $node = $bucketLinkedList->find(['callback' => function ($nodeValue) use ($key) {
            return $nodeValue['key'] === $key;
        }]);
        return $node ? $node->value['value'] : null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->keys);
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return array_keys($this->keys);
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return array_reduce($this->buckets, function ($values, LinkedList $bucket) {
            $bucketValues = array_map(function (LinkedListNode $linkedListNode) {
                return $linkedListNode->value['value'];
            }, $bucket->toArray());
            return array_merge($values, $bucketValues);
        }, []);
    }
}
