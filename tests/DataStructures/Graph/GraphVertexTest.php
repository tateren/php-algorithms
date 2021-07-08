<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Graph\GraphEdge;
use Tateren\PhpAlgorithms\DataStructures\Graph\GraphVertex;

it('should throw an error when trying to create vertex without value', function () {
    new GraphVertex();
})->throws(Exception::class);

it('should create graph vertex', function () {
    $vertex = new GraphVertex('A');

    expect($vertex)->not()->toBeNull();
    expect($vertex->value)->toBe('A');
    expect($vertex->toString())->toBe('A');
    expect($vertex->getKey())->toBe('A');
    expect($vertex->edges->toString())->toBe('');
    expect($vertex->getEdges())->toEqual([]);
});

it('should add edges to vertex and check if it exists', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $vertexA->addEdge($edgeAB);

    expect($vertexA->hasEdge($edgeAB))->toBe(true);
    expect($vertexB->hasEdge($edgeAB))->toBe(false);
    expect(count($vertexA->getEdges()))->toBe(1);
    expect($vertexA->getEdges()[0]->toString())->toBe('A_B');
});

it('should delete edges from vertex', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeAC = new GraphEdge($vertexA, $vertexC);
    $vertexA->addEdge($edgeAB)->addEdge($edgeAC);

    expect($vertexA->hasEdge($edgeAB))->toBe(true);
    expect($vertexB->hasEdge($edgeAB))->toBe(false);

    expect($vertexA->hasEdge($edgeAC))->toBe(true);
    expect($vertexC->hasEdge($edgeAC))->toBe(false);

    expect(count($vertexA->getEdges()))->toBe(2);

    expect($vertexA->getEdges()[0]->toString())->toBe('A_B');
    expect($vertexA->getEdges()[1]->toString())->toBe('A_C');

    $vertexA->deleteEdge($edgeAB);
    expect($vertexA->hasEdge($edgeAB))->toBe(false);
    expect($vertexA->hasEdge($edgeAC))->toBe(true);
    expect($vertexA->getEdges()[0]->toString())->toBe('A_C');

    $vertexA->deleteEdge($edgeAC);
    expect($vertexA->hasEdge($edgeAB))->toBe(false);
    expect($vertexA->hasEdge($edgeAC))->toBe(false);
    expect(count($vertexA->getEdges()))->toBe(0);
});

it('should delete all edges from vertex', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeAC = new GraphEdge($vertexA, $vertexC);
    $vertexA->addEdge($edgeAB)->addEdge($edgeAC);

    expect($vertexA->hasEdge($edgeAB))->toBe(true);
    expect($vertexB->hasEdge($edgeAB))->toBe(false);

    expect($vertexA->hasEdge($edgeAC))->toBe(true);
    expect($vertexC->hasEdge($edgeAC))->toBe(false);

    expect(count($vertexA->getEdges()))->toBe(2);

    $vertexA->deleteAllEdges();

    expect($vertexA->hasEdge($edgeAB))->toBe(false);
    expect($vertexB->hasEdge($edgeAB))->toBe(false);

    expect($vertexA->hasEdge($edgeAC))->toBe(false);
    expect($vertexC->hasEdge($edgeAC))->toBe(false);

    expect(count($vertexA->getEdges()))->toBe(0);
});

it('should return vertex neighbors in case if current node is start one', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeAC = new GraphEdge($vertexA, $vertexC);
    $vertexA->addEdge($edgeAB)->addEdge($edgeAC);

    expect($vertexB->getNeighbors())->toEqual([]);

    $neighbors = $vertexA->getNeighbors();

    expect(count($neighbors))->toBe(2);
    expect($neighbors[0])->toEqual($vertexB);
    expect($neighbors[1])->toEqual($vertexC);
});

it('should return vertex neighbors in case if current node is end one', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeBA = new GraphEdge($vertexB, $vertexA);
    $edgeCA = new GraphEdge($vertexC, $vertexA);
    $vertexA->addEdge($edgeBA)->addEdge($edgeCA);

    expect($vertexB->getNeighbors())->toEqual([]);

    $neighbors = $vertexA->getNeighbors();

    expect(count($neighbors))->toBe(2);
    expect($neighbors[0])->toEqual($vertexB);
    expect($neighbors[1])->toEqual($vertexC);
});

it('should check if vertex has specific neighbor', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $vertexA->addEdge($edgeAB);

    expect($vertexA->hasNeighbor($vertexB))->toBe(true);
    expect($vertexA->hasNeighbor($vertexC))->toBe(false);
});

it('should edge by vertex', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $vertexA->addEdge($edgeAB);

    expect($vertexA->findEdge($vertexB))->toEqual($edgeAB);
    expect($vertexA->findEdge($vertexC))->toBeNull();
});

it('should calculate vertex degree', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    expect($vertexA->getDegree())->toBe(0);

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $vertexA->addEdge($edgeAB);

    expect($vertexA->getDegree())->toBe(1);

    $edgeBA = new GraphEdge($vertexB, $vertexA);
    $vertexA->addEdge($edgeBA);

    expect($vertexA->getDegree())->toBe(2);

    $vertexA->addEdge($edgeAB);
    expect($vertexA->getDegree())->toBe(3);

    expect(count($vertexA->getEdges()))->toEqual(3);
});
