<?php

namespace Downmark\Interfaces;

use Downmark\Blocks\Block;

interface Preprocessor
{
    public function preprocess(Block $block): Block;
}
