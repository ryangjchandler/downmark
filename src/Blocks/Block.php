<?php

namespace Downmark\Blocks;

abstract class Block
{
    abstract public function toHtml(): string;
}
