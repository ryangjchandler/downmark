<?php

namespace Downmark\Blocks;

use Downmark\Parsers\InlineParser;

class UnorderedList extends Block
{
    public function __construct(public array $items = [])
    {
    }

    public function toHtml(): string
    {
        return sprintf('<ul>%s</ul>', implode('', array_map(fn (string $item) => sprintf('<li>%s</li>', InlineParser::parse($item)), $this->items)));
    }
}
