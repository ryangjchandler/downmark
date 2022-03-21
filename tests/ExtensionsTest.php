<?php

use Downmark\Blocks\Block;
use Downmark\Downmark;
use Downmark\Parsers\BlockParser;

class NoticeBlock extends Block
{
    public function __construct(public ?string $content = '', public string $type = 'info')
    {

    }

    public function toHtml(): string
    {
        return sprintf('<div class="notice notice-info">%s</div>', nl2br($this->content));
    }
}

test('block extensions work', function () {
    $parser = Downmark::create()
        ->block("/^:::(.\S?)*/", function (array $matches, Closure $peek, Closure $next, BlockParser $parser): Block {
            if ($peek() === null) {
                return new NoticeBlock(type: $matches[1] ?? 'info');
            }

            $lines = [];
            while ($peek() !== null) {
                $nextLine = $peek();

                if (preg_match("/^:::/", $nextLine)) {
                    $next();

                    return new NoticeBlock(implode('', array_map(fn (Block $line) => $line->toHtml(), $lines)));
                }

                $lines[] = $parser->parse($nextLine, $peek = $next(), $next);
            }

            return new NoticeBlock(implode('', array_map(fn (Block $line) => $line->toHtml(), $lines)));
        });

    expect($parser->parse(<<<'md'
    :::
    This is a warning!
    :::
    md))
        ->toContain('<div class="notice notice-info"><p>This is a warning!</p></div>');
});

test('inline extensions work', function () {
    $parser = Downmark::create()
        ->inline('/\/(.*?)\//', function (array $matches): string {
            return sprintf('<em>%s</em>', $matches[1]);
        })
        // Look for any inline text that matches a single `@` character followed by 1 to 15 alphanumeric (incl. underscore) characters.
        ->inline("/@([A-Za-z0-9_]{1,15})(?!\w)/", function (array $matches): string {
            return sprintf('<a href="https://twitter.com/%s" target="_blank">%s</a>', $matches[1], $matches[0]);
        });

    expect($parser->parse(<<<'md'
    This is my inline extension, /Hello, world!/.

    Visit @ryangjchandler on Twitter.
    md))
        ->toContain('<em>Hello, world!</em>');
});
