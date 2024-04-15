<?php

namespace RyanChandler\Downmark\Nodes;

use RyanChandler\Downmark\NodeList;
use RyanChandler\Downmark\Parser;

class Paragraph implements BlockNode
{
    public function __construct(
        protected NodeList $children,
    ) {}

    public static function getStartPattern(): string
    {
        return '/^(.+)$/';
    }

    public static function getEndPattern(): false
    {
        return false;
    }

    public static function produce(array $matches, Parser $parser): Node
    {
        return new Paragraph($parser->parseInline($matches[0]));
    }

    public function toHtml(): string
    {
        return sprintf('<p>%s</p>', $this->children->toHtml());
    }
}
