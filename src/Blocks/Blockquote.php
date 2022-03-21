<?php

namespace Downmark\Blocks;

use Downmark\Parsers\InlineParser;

class Blockquote extends Block
{
    public function __construct(public string $content)
    {

    }

    public function toHtml(): string
    {
        return sprintf('<blockquote>%s</blockquote>', InlineParser::parse(nl2br($this->content)));
    }
}
