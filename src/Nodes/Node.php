<?php

namespace RyanChandler\Downmark\Nodes;

interface Node
{
    public function toHtml(): string;
}
