<?php

namespace Tateren\PhpAlgorithms\DataStructures\Trie;

const HEAD_CHARACTER = '*';

class Trie
{
    public TrieNode $head;

    public function __construct()
    {
        $this->head = new TrieNode(HEAD_CHARACTER);
    }

    /**
     * @param string $word
     * @return $this
     */
    public function addWord(string $word): self
    {
        $characters = str_split($word);
        $currentNode = $this->head;

        foreach ($characters as $charIndex => $charIndexValue) {
            $isComplete = $charIndex === count($characters) - 1;
            $currentNode = $currentNode->addChild($charIndexValue, $isComplete);
        }
        return $this;
    }

    /**
     * @param string $word
     * @return $this
     */
    public function deleteWord(string $word): self
    {

        // Start depth-first deletion from the head node.
        depthFirstDelete($this->head, $word);

        return $this;
    }

    /**
     * @param string $word
     * @return string[]|null
     */
    public function suggestNextCharacters(string $word): ?array
    {
        $lastCharacter = $this->getLastCharacterNode($word);

        if (!$lastCharacter) {
            return null;
        }

        return $lastCharacter->suggestChildren();
    }

    /**
     * @param string $word
     * @return bool
     */
    public function doesWordExist(string $word): bool
    {
        $lastCharacter = $this->getLastCharacterNode($word);

        return $lastCharacter !== null && $lastCharacter->isCompleteWord;
    }

    /**
     * @param string $word
     * @return mixed|TrieNode|null
     */
    public function getLastCharacterNode(string $word)
    {
        $characters = str_split($word);
        $currentNode = $this->head;

        foreach ($characters as $charIndexValue) {
            if (!$currentNode->hasChild($charIndexValue)) {
                return null;
            }
            $currentNode = $currentNode->getChild($charIndexValue);
        }
        return $currentNode;
    }
}

/**
 * @param $currentNode
 * @param string $word
 * @param int $charIndex
 */
function depthFirstDelete($currentNode, string $word, int $charIndex = 0)
{
    if ($charIndex >= strlen($word)) {
        return;
    }

    $character = $word[$charIndex];
    $nextNode = $currentNode->getChild($character);

    if ($nextNode === null) {
        return;
    }

    depthFirstDelete($nextNode, $word, $charIndex + 1);

    if ($charIndex === (strlen($word) - 1)) {
        $nextNode->isCompleteWord = false;
    }

    $currentNode->removeChild($character);
}
