<?php

namespace RyanChandler\Downmark;

use RyanChandler\Downmark\Nodes\Node;

class Document extends NodeList implements Node
{
    public function toHtml(): string
    {
        return implode("\n", array_map(fn (Node $node) => $node->toHtml(), $this->nodes));
    }
}
