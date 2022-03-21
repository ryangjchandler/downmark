# A speedy Markdown parser for PHP applications.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/downmark.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/downmark)
[![Tests](https://github.com/ryangjchandler/downmark/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/ryangjchandler/downmark/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/downmark.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/downmark)

This is a super lightweight Markdown parser for PHP projects and applications. It has a rather verbose but powerful extension API for adding custom blocks and inline elements.

## Features

At the moment, Downmark has support for the following Markdown blocks:

* Headings (h1 - h6)
* Blockquotes (multi-line support)
* Single-level unordered and ordered lists
* Backtick-delimited code blocks

It also has support for the following inline elements:

* Links
* Images
* Bold, italic and strikethrough
* Inline code


## Installation

You can install the package via Composer:

```bash
composer require ryangjchandler/downmark
```

## Usage

To parse a string of Markdown and compile it to HTML, do the following:

```php
use Downmark\Downmark;

$parser = Downmark::create();

$html = $parser->parse('**Hello!**');
```

### Block extensions

To create a custom block-level extensions, you first need to register it with the parser:

```php
use Downmark\Blocks\Block;

Downmark::create()
    ->block("/::: (.*)/", function (array $matches): Block {

    });
```

The `Downmark::block()` methods accepts 2 arguments. The first is a regular expression, used by the parser to find the start of your block. The second is a `Closure` that should return an instance of `Downmark\Blocks\Block`.

You can create an object that extends the `Downmark\Blocks\Block` class. This class only requires you to implement a single `public toHtml(): string` method.

```php
use Downmark\Blocks\Block;

class NoticeBlock extends Block
{
    public function __construct(
        protected ?string $content = '',
    ) {}

    public function toHtml(): string
    {
        return '<div class="notice">' . $this->content . '</div>';
    }
}
```

Inside of the extension callback, you can return an instance of `NoticeBlock`.

```php
Downmark::create()
    ->block("/::: (.*)/", function (array $matches): Block {
        return new NoticeBlock($matches[1]);
    });
```

When the parser compiles your Markdown, it will check if this block matches and execute the callback function.

> **Note**: Documentation on building multi-line blocks coming soon... If you're super eager, source-dive the tests to find out how it works.

### Inline extensions

Downmark also provides an API for extending Markdown with custom inline elements. The example below extends Downmark to support a "mention" syntax that generates links to Twitter profiles.

```php
Downmark::create()
    // Look for any inline text that matches a single `@` character followed by 1 to 15 alphanumeric (incl. underscore) characters.
    ->inline("/@([A-Za-z0-9_]{1,15})(?!\w)/", function (array $matches): string {
        return sprintf('<a href="https://twitter.com/%s" target="_blank">%s</a>', $matches[1], $matches[0]);
    });
```

The callback function should return a `string`. This will be used to replace the regular-expression match.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
