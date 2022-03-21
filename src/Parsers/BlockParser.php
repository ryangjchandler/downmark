<?php

namespace Downmark\Parsers;

use Closure;
use Downmark\Blocks\Block;
use Downmark\Blocks\Blockquote;
use Downmark\Blocks\CodeBlock;
use Downmark\Blocks\Heading;
use Downmark\Blocks\ListItem;
use Downmark\Blocks\Paragraph;
use Downmark\Enums\HeadingLevel;
use Downmark\Enums\ListItemType;
use Spatie\Macroable\Macroable;

/** @internal */
class BlockParser
{
    use Macroable;

    public const HEADING_PATTERN = "/^(#{1,6}) +(.*)/";
    public const QUOTE_PATTERN = "/^> ?(.*)?/";
    public const UNORDERED_LIST_ITEM_PATTERN = "/^- ?(.*)?/";
    public const ORDERED_LIST_ITEM_PATTERN = "/^([0-9]+)\. ?(.*)?/";
    public const CODE_BLOCK_OPEN_PATTERN = "/```(.\S*)*/";
    public const PREG_MATCH_FOUND = 1;

    /** @var array<string, Closure> */
    protected array $extensions = [];

    public function extend(string $pattern, Closure $callback): void
    {
        $this->extensions[$pattern] = $callback;
    }

    public function parse(string $line, Closure $peek, Closure $next): Block
    {
        if (preg_match(self::CODE_BLOCK_OPEN_PATTERN, $line, $matches) === self::PREG_MATCH_FOUND) {
            $nextLine = $peek();

            if ($nextLine === null) {
                $next();

                return new CodeBlock(language: $matches[1] ?? null);
            }

            $code = [];
            while ($peek() !== null) {
                $nextLine = $peek();

                if (preg_match(self::CODE_BLOCK_OPEN_PATTERN, $nextLine) === self::PREG_MATCH_FOUND) {
                    $next();

                    return new CodeBlock(implode("\n", $code), language: $matches[1] ?? null);
                }

                $code[] = $nextLine;
                $peek = $next();
            }

            return new CodeBlock(implode("\n", $code), language: $matches[1] ?? null);
        }

        if (preg_match(self::HEADING_PATTERN, $line, $matches) === self::PREG_MATCH_FOUND) {
            return new Heading($matches[2], HeadingLevel::from(strlen($matches[1])));
        }

        if (preg_match(self::QUOTE_PATTERN, $line, $matches) === self::PREG_MATCH_FOUND) {
            return new Blockquote($matches[1]);
        }

        if (preg_match(self::UNORDERED_LIST_ITEM_PATTERN, $line, $matches) === self::PREG_MATCH_FOUND) {
            return new ListItem($matches[1], ListItemType::Unordered);
        }

        if (preg_match(self::ORDERED_LIST_ITEM_PATTERN, $line, $matches) === self::PREG_MATCH_FOUND) {
            return new ListItem($matches[2], ListItemType::Ordered, (int) $matches[1]);
        }

        foreach ($this->extensions as $pattern => $callback) {
            if (preg_match($pattern, $line, $matches) === self::PREG_MATCH_FOUND) {
                return $callback($matches, $peek, $next, $this);
            }
        }

        return new Paragraph($line);
    }
}
