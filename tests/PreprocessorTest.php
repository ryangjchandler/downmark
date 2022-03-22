<?php

use Downmark\Blocks\Block;
use Downmark\Blocks\Paragraph;
use Downmark\Downmark;
use Downmark\Interfaces\Preprocessor;

class RickRoll implements Preprocessor
{
    public function preprocess(Block $block): Block
    {
        if (! $block instanceof Paragraph) {
            return $block;
        }

        $block->content = 'Rick rolled!';

        return $block;
    }
}

test('preprocessor can modify block', function () {
    $parser = Downmark::create()
        ->preprocessor(new RickRoll());

    expect($parser->parse(<<<'md'
    Hello, world!
    md))
        ->not->toContain('Hello, world!')
        ->toContain('Rick rolled!');
});
