<?php

namespace Downmark\Blocks;

class CodeBlock extends Block
{
    public function __construct(public ?string $code = null, public ?string $language = null)
    {

    }

    public function toHtml(): string
    {
        return sprintf("<pre><code %s>\n%s\n</code></pre>", $this->language ? sprintf('class="language-%s"', $this->language) : null, htmlentities($this->code));
    }
}
