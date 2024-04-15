<?php

namespace RyanChandler\Downmark\Nodes;

use RyanChandler\Downmark\Parser;

interface Node
{
    public function toHtml(): string;
}
