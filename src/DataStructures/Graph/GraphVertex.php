<?php

declare(strict_types=1);

namespace Tateren\PhpAlgorithms\DataStructures\Graph;

use Exception;
use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedList;
use Tateren\PhpAlgorithms\DataStructures\LinkedList\LinkedListNode;

class GraphVertex
{
    /**
     * @var mixed
     */
    public $value;
    public LinkedList $edges;

    /**
     * @param null $value
     * @throws Exception
     */
    public function __construct($value = null)
    {
        if ($value === null) {
            throw new Exception('Graph vertex must have a value');
        }

        /**
         * @param GraphEdge $edgeA
         * @param GraphEdge $edgeB
         * @return int
         */
        $edgeComparator = function (GraphEdge $edgeA, GraphEdge $edgeB) {
            return $edgeA->getKey() <=> $edgeB->getKey();
        };

        $this->value = $value;
        $this->edges = new LinkedList($edgeComparator);
    }

    /**
     * @param GraphEdge $edge
     * @return $this
     */
    public function addEdge(GraphEdge $edge): self
    {
        $this->edges->append($edge);

        return $this;
    }

    public function deleteEdge(GraphEdge $edge): void
    {
        $this->edges->delete($edge);
    }

    /**
     * @return GraphVertex[]
     */
    public function getNeighbors(): array
    {
        $edges = $this->edges->toArray();
        return array_map(function (LinkedListNode $node) {
            // TODO あやしい
            return $node->value->startVertex === $this ? $node->value->endVertex : $node->value->startVertex;
        }, $edges);
    }

    /**
     * @return GraphEdge[]
     */
    public function getEdges(): array
    {
        return array_map(function (LinkedListNode $linkedListNode) {
            return $linkedListNode->value;
        }, $this->edges->toArray());
    }

    /**
     * @return int
     */
    public function getDegree(): int
    {
        return count($this->edges->toArray());
    }

    /**
     * @param GraphEdge $requiredEdge
     * @return bool
     */
    public function hasEdge(GraphEdge $requiredEdge): bool
    {
        $edgeNodes = array_filter($this->edges->toArray(), function (LinkedListNode $node) use ($requiredEdge) {
            return $node->value === $requiredEdge;
        });

        return count($edgeNodes) > 0;
    }

    /**
     * @param GraphVertex $vertex
     * @return bool
     */
    public function hasNeighbor(GraphVertex $vertex): bool
    {
        $vertexNodes = array_filter($this->edges->toArray(), function (LinkedListNode $node) use ($vertex) {
            return $node->value->startVertex === $vertex || $node->value->endVertex === $vertex;
        });

        return count($vertexNodes) > 0;
    }

    /**
     * @param GraphVertex $vertex
     * @return ?GraphEdge
     */
    public function findEdge(GraphVertex $vertex): ?GraphEdge
    {
        $edges = array_filter($this->edges->toArray(), function (LinkedListNode $node) use ($vertex) {
            return $node->value->startVertex === $vertex || $node->value->endVertex === $vertex;
        });
        $edge = array_pop($edges);
        return $edge->value ?? null;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->value;
    }

    /**
     * @return GraphVertex
     */
    public function deleteAllEdges(): GraphVertex
    {
        foreach ($this->getEdges() as $edge) {
            $this->deleteEdge($edge);
        }
        return $this;
    }

    /**
     * @param ?callable $callback
     * @return string
     */
    public function toString(callable $callback = null): string
    {
        return $callback ? $callback($this->value) : $this->value;
    }
}
