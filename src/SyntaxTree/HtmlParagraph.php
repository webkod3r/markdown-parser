<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

class HtmlParagraph extends HtmlContainerElement
{
    /**
     * Creates a paragraph object representation
     */
    public function __construct()
    {
        $this->tag = 'p';
        $this->children = [];
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitParagraph($this);
    }
}
