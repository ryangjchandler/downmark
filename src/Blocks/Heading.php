<?php

namespace Downmark\Blocks;

use Downmark\Enums\HeadingLevel;

class Heading extends Block
{
    public function __construct(public string $content, public HeadingLevel $level)
    {
    }

    public function toHtml(): string
    {
        return sprintf('<h%1$s>%2$s</h%1$s>', $this->level->value, $this->content);
    }
}
