<?php

use RyanChandler\Downmark\Parser;

it('can parse headings', function () {
    $parser = new Parser();
    $document = $parser->parse('# Hello, world!');

    expect($document->toHtml())->toBe('<h1>Hello, world!</h1>');
});

it('can parse paragraphs', function () {
    $parser = new Parser();
    $document = $parser->parse('Hello, world!');

    expect($document->toHtml())->toBe('<p>Hello, world!</p>');
});
