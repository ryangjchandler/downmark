<?php

namespace Downmark\Blocks;

use Downmark\Parsers\InlineParser;

class OrderedList extends Block
{
    public function __construct(public array $items = [])
    {
    }

    public function toHtml(): string
    {
        return sprintf('<ol>%s</ol>', implode('', array_map(fn (string $item) => sprintf('<li>%s</li>', InlineParser::parse($item)), $this->items)));
    }
}
