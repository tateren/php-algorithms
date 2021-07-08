<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Graph\Graph;
use Tateren\PhpAlgorithms\DataStructures\Graph\GraphEdge;
use Tateren\PhpAlgorithms\DataStructures\Graph\GraphVertex;

it('should add vertices to graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    $graph->addVertex($vertexA)->addVertex($vertexB);

    expect($graph->toString())->toBe('A,B');
    expect($graph->getVertexByKey($vertexA->getKey()))->toEqual($vertexA);
    expect($graph->getVertexByKey($vertexB->getKey()))->toEqual($vertexB);
});

it('should add edges to undirected graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    $edgeAB = new GraphEdge($vertexA, $vertexB);

    $graph->addEdge($edgeAB);

    expect(count($graph->getAllVertices()))->toBe(2);
    expect($graph->getAllVertices()[0])->toEqual($vertexA);
    expect($graph->getAllVertices()[1])->toEqual($vertexB);

    $graphVertexA = $graph->getVertexByKey($vertexA->getKey());
    $graphVertexB = $graph->getVertexByKey($vertexB->getKey());

    expect($graph->toString())->toBe('A,B');
    expect($graphVertexA)->not()->toBeNull();
    expect($graphVertexB)->not()->toBeNull();

    expect($graph->getVertexByKey('not existing'))->toBeNull();

    expect(count($graphVertexA->getNeighbors()))->toBe(1);
    expect($graphVertexA->getNeighbors()[0])->toEqual($vertexB);
    expect($graphVertexA->getNeighbors()[0])->toEqual($graphVertexB);

    expect(count($graphVertexB->getNeighbors()))->toBe(1);
    expect($graphVertexB->getNeighbors()[0])->toEqual($vertexA);
    expect($graphVertexB->getNeighbors()[0])->toEqual($graphVertexA);
});

it('should add edges to directed graph', function () {
    $graph = new Graph(true);

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    $edgeAB = new GraphEdge($vertexA, $vertexB);

    $graph->addEdge($edgeAB);

    $graphVertexA = $graph->getVertexByKey($vertexA->getKey());
    $graphVertexB = $graph->getVertexByKey($vertexB->getKey());

    expect($graph->toString())->toBe('A,B');
    expect($graphVertexA)->not()->toBeNull();
    expect($graphVertexB)->not()->toBeNull();

    expect(count($graphVertexA->getNeighbors()))->toBe(1);
    expect($graphVertexA->getNeighbors()[0])->toEqual($vertexB);
    expect($graphVertexA->getNeighbors()[0])->toEqual($graphVertexB);

    expect(count($graphVertexB->getNeighbors()))->toBe(0);
});

it('should find edge by vertices in undirected graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB, 10);

    $graph->addEdge($edgeAB);

    $graphEdgeAB = $graph->findEdge($vertexA, $vertexB);
    $graphEdgeBA = $graph->findEdge($vertexB, $vertexA);
    $graphEdgeAC = $graph->findEdge($vertexA, $vertexC);
    $graphEdgeCA = $graph->findEdge($vertexC, $vertexA);

    expect($graphEdgeAC)->toBeNull();
    expect($graphEdgeCA)->toBeNull();
    expect($graphEdgeAB)->toEqual($edgeAB);
    expect($graphEdgeBA)->toEqual($edgeAB);
    expect($graphEdgeAB->weight)->toBe(10);
});

it('should find edge by vertices in directed graph', function () {
    $graph = new Graph(true);

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB, 10);

    $graph->addEdge($edgeAB);

    $graphEdgeAB = $graph->findEdge($vertexA, $vertexB);
    $graphEdgeBA = $graph->findEdge($vertexB, $vertexA);
    $graphEdgeAC = $graph->findEdge($vertexA, $vertexC);
    $graphEdgeCA = $graph->findEdge($vertexC, $vertexA);

    expect($graphEdgeAC)->toBeNull();
    expect($graphEdgeCA)->toBeNull();
    expect($graphEdgeBA)->toBeNull();
    expect($graphEdgeAB)->toEqual($edgeAB);
    expect($graphEdgeAB->weight)->toBe(10);
});

it('should return vertex neighbors', function () {
    $graph = new Graph(true);

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeAC = new GraphEdge($vertexA, $vertexC);

    $graph->addEdge($edgeAB)->addEdge($edgeAC);

    $neighbors = $graph->getNeighbors($vertexA);

    expect(count($neighbors))->toBe(2);
    expect($neighbors[0])->toEqual($vertexB);
    expect($neighbors[1])->toEqual($vertexC);
});

it('should throw an error when trying to add edge twice', function () {
    $graph = new Graph(true);

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');

    $edgeAB = new GraphEdge($vertexA, $vertexB);

    $graph->addEdge($edgeAB)->addEdge($edgeAB);
})->throws(Exception::class);

it('should return the list of all added edges', function () {
    $graph = new Graph(true);

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);

    $graph->addEdge($edgeAB)->addEdge($edgeBC);

    $edges = $graph->getAllEdges();

    expect(count($edges))->toBe(2);
    expect($edges[0])->toEqual($edgeAB);
    expect($edges[1])->toEqual($edgeBC);
});

it('should calculate total graph weight for default graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);
    $edgeCD = new GraphEdge($vertexC, $vertexD);
    $edgeAD = new GraphEdge($vertexA, $vertexD);

    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeCD)->addEdge($edgeAD);

    expect($graph->getWeight())->toBe(0);
});

it('should calculate total graph weight for weighted graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB, 1);
    $edgeBC = new GraphEdge($vertexB, $vertexC, 2);
    $edgeCD = new GraphEdge($vertexC, $vertexD, 3);
    $edgeAD = new GraphEdge($vertexA, $vertexD, 4);

    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeCD)->addEdge($edgeAD);

    expect($graph->getWeight())->toBe(10);
});

it('should be possible to delete edges from graph', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);
    $edgeAC = new GraphEdge($vertexA, $vertexC);

    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeAC);

    expect(count($graph->getAllEdges()))->toBe(3);

    $graph->deleteEdge($edgeAB);

    expect(count($graph->getAllEdges()))->toBe(2);
    expect($graph->getAllEdges()[0]->getKey())->toBe($edgeBC->getKey());
    expect($graph->getAllEdges()[1]->getKey())->toBe($edgeAC->getKey());
});

it('should should throw an error when trying to delete not existing edge', function () {
    $graph = new Graph();

    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);

    $graph->addEdge($edgeAB);
    $graph->deleteEdge($edgeBC);
})->throws(Exception::class);

it('should be possible to reverse graph', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeAC = new GraphEdge($vertexA, $vertexC);
    $edgeCD = new GraphEdge($vertexC, $vertexD);

    $graph = new Graph(true);
    $graph->addEdge($edgeAB)->addEdge($edgeAC)->addEdge($edgeCD);

    expect($graph->toString())->toBe('A,B,C,D');
    expect(count($graph->getAllEdges()))->toBe(3);
    expect(count($graph->getNeighbors($vertexA)))->toBe(2);
    expect($graph->getNeighbors($vertexA)[0]->getKey())->toBe($vertexB->getKey());
    expect($graph->getNeighbors($vertexA)[1]->getKey())->toBe($vertexC->getKey());
    expect(count($graph->getNeighbors($vertexB)))->toBe(0);
    expect(count($graph->getNeighbors($vertexC)))->toBe(1);
    expect($graph->getNeighbors($vertexC)[0]->getKey())->toBe($vertexD->getKey());
    expect(count($graph->getNeighbors($vertexD)))->toBe(0);

    $graph->reverse();

    expect($graph->toString())->toBe('A,B,C,D');
    expect(count($graph->getAllEdges()))->toBe(3);
    expect(count($graph->getNeighbors($vertexA)))->toBe(0);
    expect(count($graph->getNeighbors($vertexB)))->toBe(1);
    expect($graph->getNeighbors($vertexB)[0]->getKey())->toBe($vertexA->getKey());
    expect(count($graph->getNeighbors($vertexC)))->toBe(1);
    expect($graph->getNeighbors($vertexC)[0]->getKey())->toBe($vertexA->getKey());
    expect(count($graph->getNeighbors($vertexD)))->toBe(1);
    expect($graph->getNeighbors($vertexD)[0]->getKey())->toBe($vertexC->getKey());
});

it('should return vertices indices', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);
    $edgeCD = new GraphEdge($vertexC, $vertexD);
    $edgeBD = new GraphEdge($vertexB, $vertexD);

    $graph = new Graph();
    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeCD)->addEdge($edgeBD);

    $verticesIndices = $graph->getVerticesIndices();
    expect($verticesIndices)->toEqual(['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3]);
});

it('should generate adjacency matrix for undirected graph', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB);
    $edgeBC = new GraphEdge($vertexB, $vertexC);
    $edgeCD = new GraphEdge($vertexC, $vertexD);
    $edgeBD = new GraphEdge($vertexB, $vertexD);

    $graph = new Graph();
    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeCD)->addEdge($edgeBD);

    $adjacencyMatrix = $graph->getAdjacencyMatrix();
    expect($adjacencyMatrix)->toEqual([
        [INF, 0, INF, INF],
        [0, INF, 0, 0],
        [INF, 0, INF, 0],
        [INF, 0, 0, INF],
    ]);
});

it('should generate adjacency matrix for directed graph', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $vertexC = new GraphVertex('C');
    $vertexD = new GraphVertex('D');

    $edgeAB = new GraphEdge($vertexA, $vertexB, 2);
    $edgeBC = new GraphEdge($vertexB, $vertexC, 1);
    $edgeCD = new GraphEdge($vertexC, $vertexD, 5);
    $edgeBD = new GraphEdge($vertexB, $vertexD, 7);

    $graph = new Graph(true);
    $graph->addEdge($edgeAB)->addEdge($edgeBC)->addEdge($edgeCD)->addEdge($edgeBD);

    $adjacencyMatrix = $graph->getAdjacencyMatrix();
    expect($adjacencyMatrix)->toEqual([
        [INF, 2, INF, INF],
        [INF, INF, 1, 7],
        [INF, INF, INF, 5],
        [INF, INF, INF, INF],
    ]);
});
