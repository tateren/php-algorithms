<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Trie\TrieNode;

it('should create trie node', function () {
    $trieNode = new TrieNode('c', true);

    expect($trieNode->character)->toBe('c');
    expect($trieNode->isCompleteWord)->toBe(true);
    expect($trieNode->toString())->toBe('c*');
});

it('should add child nodes', function () {
    $trieNode = new TrieNode('c');

    $trieNode->addChild('a', true);
    $trieNode->addChild('o');

    expect($trieNode->toString())->toBe('c:a,o');
});

it('should get child nodes', function () {
    $trieNode = new TrieNode('c');

    $trieNode->addChild('a');
    $trieNode->addChild('o');

    expect($trieNode->getChild('a')->toString())->toBe('a');
    expect($trieNode->getChild('a')->character)->toBe('a');
    expect($trieNode->getChild('o')->toString())->toBe('o');
    expect($trieNode->getChild('b'))->toBeNull();
});

it('should check if node has children', function () {
    $trieNode = new TrieNode('c');

    expect($trieNode->hasChildren())->toBe(false);

    $trieNode->addChild('a');

    expect($trieNode->hasChildren())->toBe(true);
});

it('should check if node has specific child', function () {
    $trieNode = new TrieNode('c');

    $trieNode->addChild('a');
    $trieNode->addChild('o');

    expect($trieNode->hasChild('a'))->toBe(true);
    expect($trieNode->hasChild('o'))->toBe(true);
    expect($trieNode->hasChild('b'))->toBe(false);
});

it('should suggest next children', function () {
    $trieNode = new TrieNode('c');

    $trieNode->addChild('a');
    $trieNode->addChild('o');

    expect($trieNode->suggestChildren())->toEqual(['a', 'o']);
});

it('should delete child node if the child node has NO children', function () {
    $trieNode = new TrieNode('c');
    $trieNode->addChild('a');
    expect($trieNode->hasChild('a'))->toBe(true);

    $trieNode->removeChild('a');
    expect($trieNode->hasChild('a'))->toBe(false);
});

it('should NOT delete child node if the child node has children', function () {
    $trieNode = new TrieNode('c');
    $trieNode->addChild('a');
    $childNode = $trieNode->getChild('a');
    $childNode->addChild('r');

    $trieNode->removeChild('a');
    expect($trieNode->hasChild('a'))->toEqual(true);
});

it('should NOT delete child node if the child node completes a word', function () {
    $trieNode = new TrieNode('c');
    $IS_COMPLETE_WORD = true;
    $trieNode->addChild('a', $IS_COMPLETE_WORD);

    $trieNode->removeChild('a');
    expect($trieNode->hasChild('a'))->toEqual(true);
});
