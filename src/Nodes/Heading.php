<?php

namespace RyanChandler\Downmark\Nodes;

use RyanChandler\Downmark\NodeList;
use RyanChandler\Downmark\Parser;

class Heading implements BlockNode
{
    public function __construct(
        public HeadingLevel $level,
        public NodeList $content,
    ) {
    }

    public static function produce(array $matches, Parser $parser): Node
    {
        return new Heading(
            match (strlen($matches[1])) {
                1 => HeadingLevel::H1,
                2 => HeadingLevel::H2,
                3 => HeadingLevel::H3,
                4 => HeadingLevel::H4,
                5 => HeadingLevel::H5,
                6 => HeadingLevel::H6,
            },
            $parser->parseInline($matches[2]),
        );
    }

    public static function getStartPattern(): string
    {
        return '/^(#{1,6}) (.+)$/';
    }

    public static function getEndPattern(): false
    {
        return false;
    }

    public function toHtml(): string
    {
        return sprintf('<%s>%s</%s>', $this->level->toHtmlTag(), $this->content->toHtml(), $this->level->toHtmlTag());
    }
}

enum HeadingLevel
{
    case H1;
    case H2;
    case H3;
    case H4;
    case H5;
    case H6;

    public function toHtmlTag(): string
    {
        return match ($this) {
            self::H1 => 'h1',
            self::H2 => 'h2',
            self::H3 => 'h3',
            self::H4 => 'h4',
            self::H5 => 'h5',
            self::H6 => 'h6',
        };
    }
}
