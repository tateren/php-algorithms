<?php

declare(strict_types=1);

use Tateren\PhpAlgorithms\DataStructures\Graph\GraphEdge;
use Tateren\PhpAlgorithms\DataStructures\Graph\GraphVertex;

it('should create graph edge with default weight', function () {
    $startVertex = new GraphVertex('A');
    $endVertex = new GraphVertex('B');
    $edge = new GraphEdge($startVertex, $endVertex);

    expect($edge->getKey())->toBe('A_B');
    expect($edge->toString())->toBe('A_B');
    expect($edge->startVertex)->toEqual($startVertex);
    expect($edge->endVertex)->toEqual($endVertex);
    expect($edge->weight)->toEqual(0);
});

it('should create graph edge with predefined weight', function () {
    $startVertex = new GraphVertex('A');
    $endVertex = new GraphVertex('B');
    $edge = new GraphEdge($startVertex, $endVertex, 10);

    expect($edge->startVertex)->toEqual($startVertex);
    expect($edge->endVertex)->toEqual($endVertex);
    expect($edge->weight)->toEqual(10);
});

it('should be possible to do edge reverse', function () {
    $vertexA = new GraphVertex('A');
    $vertexB = new GraphVertex('B');
    $edge = new GraphEdge($vertexA, $vertexB, 10);

    expect($edge->startVertex)->toEqual($vertexA);
    expect($edge->endVertex)->toEqual($vertexB);
    expect($edge->weight)->toEqual(10);

    $edge->reverse();

    expect($edge->startVertex)->toEqual($vertexB);
    expect($edge->endVertex)->toEqual($vertexA);
    expect($edge->weight)->toEqual(10);
});
