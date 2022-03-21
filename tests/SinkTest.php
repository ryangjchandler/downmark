<?php

use Downmark\Downmark;

test('it can parse the sink', function () {
    $downmark = Downmark::create();

    expect($downmark->parse(file_get_contents(__DIR__ . '/Benchmarks/sink.md')))
        ->toBeString();
});

test('it can parse the blog post', function () {
    $downmark = Downmark::create();

    expect($downmark->parse(file_get_contents(__DIR__ . '/Benchmarks/blog-post.md')))
        ->toBeString();
});

test('it can parse the code block', function () {
    $downmark = Downmark::create();

    expect($downmark->parse(file_get_contents(__DIR__ . '/Benchmarks/code-block.md')))
        ->toBeString();
});
