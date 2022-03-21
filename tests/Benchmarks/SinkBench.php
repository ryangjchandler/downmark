<?php

namespace Downmark\Tests\Benchmarks;

use Downmark\Downmark;
use League\CommonMark\CommonMarkConverter;
use Michelf\MarkdownExtra;
use Parsedown;

class SinkBench
{
    protected string $sink;

    public function __construct()
    {
        $this->sink = file_get_contents(__DIR__ . '/sink.md');
    }

    /**
     * @Revs(200)
     * @Iterations(10)
     */
    public function benchCommonmark()
    {
        for ($i = 0; $i < 10; $i++) {
            $converter = new CommonMarkConverter();
            $converter->convert($this->sink);
        }
    }

    /**
     * @Revs(200)
     * @Iterations(10)
     */
    public function benchDownmark()
    {
        for ($i = 0; $i < 10; $i++) {
            Downmark::create()->parse($this->sink);
        }
    }

    /**
     * @Revs(200)
     * @Iterations(10)
     */
    public function benchParsedown()
    {
        for ($i = 0; $i < 10; $i++) {
            (new Parsedown())->parse($this->sink);
        }
    }

    /**
     * @Revs(200)
     * @Iterations(10)
     */
    public function benchMarkdownExtra()
    {
        for ($i = 0; $i < 10; $i++) {
            MarkdownExtra::defaultTransform($this->sink);
        }
    }
}
