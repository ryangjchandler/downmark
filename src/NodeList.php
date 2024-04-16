<?php

namespace RyanChandler\Downmark;

use RyanChandler\Downmark\Nodes\Node;

class NodeList implements Node
{
    public function __construct(
        protected array $nodes = [],
    ) {
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function toArray(): array
    {
        return $this->nodes;
    }

    public function appendNode(Node $node): void
    {
        $this->nodes[] = $node;
    }

    public function findNodeOfType(string $type): ?Node
    {
        foreach ($this->nodes as $node) {
            if ($node::class === $type) {
                return $node;
            }
        }

        return null;
    }

    public function toHtml(): string
    {
        return implode('', array_map(fn (Node $node) => $node->toHtml(), $this->nodes));
    }
}
