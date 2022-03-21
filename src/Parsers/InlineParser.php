<?php

namespace Downmark\Parsers;

use Closure;
use Spatie\Macroable\Macroable;

class InlineParser
{
    use Macroable;

    public const LINK_PATTERN = "/\[(.*?)\]\((\S?)+\)/";
    public const IMG_PATTERN = "/!\[(.*?)\]\((\S?)+\)/";
    public const BOLD_PATTERN = "/\*{2}(.*?)\*{2}/";
    public const STRONG_EM_PATTERN = "/\*{3}(.*?)\*{3}/";
    public const STRIKE_PATTERN = "/~{2}(.*?)\~{2}/";
    public const EM_PATTERN = "/_{1}(.*?)\_{1}/";
    public const CODE_PATTERN = "/\s`{1}(.*?)`{1}\s/";
    public const PREG_MATCH_FOUND = 1;

    /** @var array<string, \Closure> */
    protected static $extensions = [];

    public static function extend(string $pattern, Closure $callback): void
    {
        static::$extensions[$pattern] = $callback;
    }

    public static function parse(string $line): string
    {
        $line = preg_replace(static::IMG_PATTERN, '<img src="$2" alt="$1">', $line);
        $line = preg_replace(static::LINK_PATTERN, '<a href="$2">$1</a>', $line);
        $line = preg_replace(static::STRONG_EM_PATTERN, '<strong><em>$1</em></strong>', $line);
        $line = preg_replace(static::BOLD_PATTERN, '<strong>$1</strong>', $line);
        $line = preg_replace(static::EM_PATTERN, '<em>$1</em>', $line);
        $line = preg_replace(static::STRIKE_PATTERN, '<strike>$1</strike>', $line);
        $line = preg_replace(static::CODE_PATTERN, '<code>$1</code>', $line);

        foreach (static::$extensions as $pattern => $callback) {
            $line = preg_replace_callback($pattern, fn ($matches) => $callback($matches), $line);
        }

        return $line;
    }
}
