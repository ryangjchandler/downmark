<?php

namespace RyanChandler\Downmark;

class ParserState
{
    protected Document $document;

    public function __construct(
        protected string $input,
    ) {
        $this->document = new Document();
    }

    public function consume(int $length): void
    {
        $this->input = substr($this->input, $length);
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function isAtEnd(): bool
    {
        return $this->input === '';
    }

    public function getDocument(): Document
    {
        return $this->document;
    }
}
