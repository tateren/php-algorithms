<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Graph;

use Exception;

class Graph
{
    private array $vertices;
    private array $edges;
    private bool $isDirected;

    /**
     * @param bool $isDirected
     */
    public function __construct(bool $isDirected = false)
    {
        $this->vertices = [];
        $this->edges = [];
        $this->isDirected = $isDirected;
    }

    /**
     * @param GraphVertex $newVertex
     * @return $this
     */
    public function addVertex(GraphVertex $newVertex): self
    {
        $this->vertices[$newVertex->getKey()] = $newVertex;
        return $this;
    }

    /**
     * @param string vertexKey
     * @returns ?GraphVertex
     */
    public function getVertexByKey(string $vertexKey): ?GraphVertex
    {
        return $this->vertices[$vertexKey] ?? null;
    }

    /**
     * @param GraphVertex vertex
     * @returns GraphVertex[]
     */
    public function getNeighbors(GraphVertex $vertex): array
    {
        return $vertex->getNeighbors();
    }

    /**
     * @return GraphVertex[]
     */
    public function getAllVertices(): array
    {
        return array_values($this->vertices);
    }

    /**
     * @return GraphEdge[]
     */
    public function getAllEdges(): array
    {
        return array_values($this->edges);
    }

    /**
     * @param GraphEdge edge
     * @returns $this
     * @throws Exception
     */
    public function addEdge(GraphEdge $edge): self
    {
        $startVertex = $this->getVertexByKey($edge->startVertex->getKey());
        $endVertex = $this->getVertexByKey($edge->endVertex->getKey());

        if (!$startVertex) {
            $this->addVertex($edge->startVertex);
            $startVertex = $this->getVertexByKey($edge->startVertex->getKey());
        }

        if (!$endVertex) {
            $this->addVertex($edge->endVertex);
            $endVertex = $this->getVertexByKey($edge->endVertex->getKey());
        }

        if (!empty($this->edges[$edge->getKey()])) {
            throw new Exception('Edge has already been added before');
        } else {
            $this->edges[$edge->getKey()] = $edge;
        }

        $startVertex->addEdge($edge);
        if (!$this->isDirected) {
            $endVertex->addEdge($edge);
        }

        return $this;
    }

    /**
     * @param GraphEdge edge
     * @throws Exception
     */
    public function deleteEdge(GraphEdge $edge): void
    {
        if ($this->edges[$edge->getKey()]) {
            unset($this->edges[$edge->getKey()]);
        } else {
            throw new Exception('Edge not found in graph');
        }

        $startVertex = $this->getVertexByKey($edge->startVertex->getKey());
        $endVertex = $this->getVertexByKey($edge->endVertex->getKey());

        $startVertex->deleteEdge($edge);
        $endVertex->deleteEdge($edge);
    }

    /**
     * @param GraphVertex startVertex
     * @param GraphVertex endVertex
     * @return ?GraphEdge
     */
    public function findEdge(GraphVertex $startVertex, GraphVertex $endVertex): ?GraphEdge
    {
        $vertex = $this->getVertexByKey($startVertex->getKey());
        if (!$vertex) {
            return null;
        }

        return $vertex->findEdge($endVertex);
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return array_reduce($this->getAllEdges(), function (int $weight, GraphEdge $graphEdge) {
            return $weight + $graphEdge->weight;
        }, 0);
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function reverse(): Graph
    {
        foreach ($this->getAllEdges() as $edge) {
            $this->deleteEdge($edge);
            $edge->reverse();
            $this->addEdge($edge);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getVerticesIndices(): array
    {
        $verticesIndices = [];
        foreach ($this->getAllVertices() as $index => $vertex) {
            $verticesIndices[$vertex->getKey()] = $index;
        }
        return $verticesIndices;
    }

    /**
     * @return array[]
     */
    public function getAdjacencyMatrix(): array
    {
        $vertices = $this->getAllVertices();
        $verticesIndices = $this->getVerticesIndices();

        $adjacencyMatrix = array_map(function () use ($vertices) {
            return array_fill(0, count($vertices), INF);
        }, array_fill(0, count($vertices), null));

        foreach ($vertices as $vertexIndex => $vertex) {
            foreach ($vertex->getNeighbors() as $neighbor) {
                $neighborIndex = $verticesIndices[$neighbor->getKey()];
                $adjacencyMatrix[$vertexIndex][$neighborIndex] = $this->findEdge($vertex, $neighbor)->weight;
            }
        }
        return $adjacencyMatrix;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', array_keys($this->vertices));
    }
}
