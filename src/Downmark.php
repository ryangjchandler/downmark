<?php

namespace Downmark;

use Closure;
use Downmark\Blocks\Blank;
use Downmark\Blocks\Block;
use Downmark\Interfaces\Preprocessor;
use Downmark\Parsers\BlockParser;
use Downmark\Parsers\InlineParser;
use Downmark\Parsers\Optimizer;

const PREG_MATCH_FOUND = 1;

class Downmark
{
    protected array $lines = [];

    protected array $preprocessors = [];

    public function __construct(
        protected BlockParser $blockParser = new BlockParser(),
        protected Optimizer $optimizer = new Optimizer(),
    ) {
    }

    public function preprocessor(Preprocessor $preprocessor): static
    {
        $this->preprocessors[$preprocessor::class] = $preprocessor;

        return $this;
    }

    public function block(string $pattern, Closure $callback): static
    {
        $this->blockParser->extend($pattern, $callback);

        return $this;
    }

    public function inline(string $pattern, Closure $callback): static
    {
        InlineParser::extend($pattern, $callback);

        return $this;
    }

    public function parse(string $input): string
    {
        if (trim($input) === '') {
            return '';
        }

        // Replace tabs with 4 spaces.
        $input = str_replace("\t", '    ', $input);

        // Normalise line breaks.
        $input = str_replace(["\r\n", "\r"], "\n", $input);

        // Split into lines.
        $lines = explode("\n", $input);
        $ast = [];

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            if (trim($line) === '') {
                $ast[] = new Blank();

                continue;
            }

            $ast[] = $this->blockParser->parse($line, function () use ($i, $lines): ?string {
                return $lines[$i + 1] ?? null;
            }, function () use (&$i, $lines) {
                $i++;

                return function () use ($i, $lines): ?string {
                    return $lines[$i + 1] ?? null;
                };
            });
        }

        $ast = $this->optimizer->optimize($ast);

        foreach ($ast as &$block) {
            foreach ($this->preprocessors as $preprocessor) {
                $block = $preprocessor->preprocess($block);
            }
        }

        return implode(PHP_EOL, array_map(fn (Block $block) => $block->toHtml(), $ast));
    }

    public static function create(): static
    {
        return new static();
    }
}
