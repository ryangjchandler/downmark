<?php

namespace Downmark\Blocks;

use Downmark\Enums\ListItemType;
use Downmark\Parsers\InlineParser;

class ListItem extends Block
{
    public function __construct(public string $content, public ListItemType $type, public ?int $i = null)
    {

    }

    public function toHtml(): string
    {
        return sprintf('<li>%s</li>', InlineParser::parse($this->content));
    }
}
