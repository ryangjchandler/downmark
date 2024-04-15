<?php

namespace RyanChandler\Downmark;

use RyanChandler\Downmark\Nodes\Heading;
use RyanChandler\Downmark\Nodes\Node;
use RyanChandler\Downmark\Nodes\Paragraph;
use RyanChandler\Downmark\Nodes\Text;

class Parser
{
    /** @var array<int, array<class-string<Nodes\BlockNode>, int>> */
    protected array $blockNodes = [];

    /** @var array<int, array<class-string<Node>, int>> */
    protected array $inlineNodes = [];

    public function __construct()
    {
        $this->registerBlockNode(Heading::class);
        $this->registerBlockNode(Paragraph::class);
    }

    public function registerBlockNode(string $class, int $priority = PHP_INT_MAX): static
    {
        $this->blockNodes[] = [$class, $priority];

        usort($this->blockNodes, fn ($a, $b) => $a[1] <=> $b[1]);

        return $this;
    }

    public function registerInlineNode(string $class, int $priority = PHP_INT_MAX): static
    {
        $this->inlineNodes[] = [$class, $priority];

        usort($this->inlineNodes, fn ($a, $b) => $a[1] <=> $b[1]);

        return $this;
    }

    public function parse(string $document): Document
    {
        $state = new ParserState($document);

        while (! $state->isAtEnd()) {
            $state->getDocument()->appendNode($this->parseBlock($state));
        }

        return $state->getDocument();
    }

    public function parseBlock(ParserState $state): Node
    {
        foreach ($this->blockNodes as [$class, $_]) {
            if (preg_match($class::getStartPattern(), $state->getInput(), $matches)) {
                $state->consume(strlen($matches[0]));

                return $class::produce($matches, $this);
            }
        }

        throw new \Exception('Failed to parse a node. No block node matched.');
    }

    public function parseInline(string $text): NodeList
    {
        $state = new ParserState($text);
        $nodes = [];

        while (true) {
            foreach ($this->inlineNodes as [$class, $_]) {
                if (preg_match($class::getPattern(), $state->getInput(), $matches)) {
                    $state->consume(strlen($matches[0]));

                    $nodes[] = $class::produce($matches, $this);

                    continue 2;
                }
            }

            break;
        }

        if (! $state->isAtEnd()) {
            $nodes[] = new Text($state->getInput());
        }

        return new NodeList($nodes);
    }
}
