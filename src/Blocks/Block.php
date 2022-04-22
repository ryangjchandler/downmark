<?php

namespace Downmark\Blocks;

use Closure;

abstract class Block
{
    abstract public function toHtml(): string;

    protected ?Closure $renderer = null;

    public function renderUsing(Closure $callback): void
    {
        $this->renderer = $callback;
    }
}
