<?php

namespace RyanChandler\Downmark\Nodes;

use RyanChandler\Downmark\Parser;

interface BlockNode extends Node
{
    /**
     * Get the pattern that is used to detect the start of the block.
     */
    public static function getStartPattern(): string;

    /**
     * Get the pattern that is used to detect the end of the block.
     *
     * If the block does not have an end pattern, i.e. it's a single-line block node,
     * then this method should return false.
     */
    public static function getEndPattern(): string|false;

    /**
     * Take the array of matches from the RegEx and return a new instance of the block node.
     */
    public static function produce(array $matches, Parser $parser): Node;
}
