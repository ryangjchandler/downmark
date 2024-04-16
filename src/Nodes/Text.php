<?php

namespace RyanChandler\Downmark\Nodes;

use RyanChandler\Downmark\Parser;

class Text implements Node
{
    public function __construct(
        public string $text,
    ) {
    }

    public static function produce(array $matches, Parser $parser): Text
    {
        return new Text($matches[0][0]);
    }

    public function toHtml(): string
    {
        return htmlspecialchars($this->text);
    }
}
