<?php

namespace Tateren\PhpAlgorithms\DataStructures\Trie;

use Tateren\PhpAlgorithms\DataStructures\HashTable\HashTable;

class TrieNode
{
    public string $character;
    public bool $isCompleteWord;
    private HashTable $children;

    /**
     * @param string $character
     * @param bool $isCompleteWord
     */
    public function __construct(string $character, bool $isCompleteWord = false)
    {
        $this->character = $character;
        $this->isCompleteWord = $isCompleteWord;
        $this->children = new HashTable();
    }

    /**
     * @param string $character
     * @return mixed|null
     */
    public function getChild(string $character)
    {
        return $this->children->get($character);
    }

    /**
     * @param string $character
     * @param bool $isCompleteWord
     * @return self
     */
    public function addChild(string $character, bool $isCompleteWord = false): self
    {
        if (!$this->children->has($character)) {
            $this->children->set($character, new TrieNode($character, $isCompleteWord));
        }

        $childNode = $this->children->get($character);

        $childNode->isCompleteWord = $childNode->isCompleteWord ?: $isCompleteWord;

        return $childNode;
    }

    /**
     * @param string $character
     * @return $this
     */
    public function removeChild(string $character): self
    {

        $childNode = $this->getChild($character);

        if (
            $childNode
            && !$childNode->isCompleteWord
            && !$childNode->hasChildren()
        ) {
            $this->children->delete($character);
        }

        return $this;
    }

    /**
     * @param string $character
     * @return bool
     */
    public function hasChild(string $character): bool
    {
        return $this->children->has($character);
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children->getKeys()) !== 0;
    }

    /**
     * @return string[]
     */
    public function suggestChildren(): array
    {
        return $this->children->getKeys();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        $childrenAsString = implode(',', $this->suggestChildren());
        $childrenAsString = $childrenAsString ? ":" . $childrenAsString : '';
        $isCompleteString = $this->isCompleteWord ? '*' : '';
        return $this->character . $isCompleteString . $childrenAsString;
    }
}
