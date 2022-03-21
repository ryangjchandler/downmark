<?php

namespace Downmark\Blocks;

use Downmark\Parsers\InlineParser;

class Paragraph extends Block
{
    public function __construct(public string $content)
    {
    }

    public function toHtml(): string
    {
        return sprintf('<p>%s</p>', InlineParser::parse($this->content));
    }
}
