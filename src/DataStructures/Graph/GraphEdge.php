<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Graph;

class GraphEdge
{
    public GraphVertex $startVertex;
    public GraphVertex $endVertex;
    public int $weight;

    /**
     * GraphEdge constructor.
     * @param GraphVertex $startVertex
     * @param GraphVertex $endVertex
     * @param int $weight
     */
    public function __construct(GraphVertex $startVertex, GraphVertex $endVertex, int $weight = 0)
    {
        $this->startVertex = $startVertex;
        $this->endVertex = $endVertex;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        $startVertexKey = $this->startVertex->getKey();
        $endVertexKey = $this->endVertex->getKey();

        return "${startVertexKey}_${endVertexKey}";
    }

    /**
     * @return $this
     */
    public function reverse(): self
    {
        [$this->startVertex, $this->endVertex] = [$this->endVertex, $this->startVertex];
        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getKey();
    }
}
