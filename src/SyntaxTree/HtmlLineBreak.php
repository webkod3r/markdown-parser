<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

class HtmlLineBreak extends HtmlElement
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tag = 'br';
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitLineBreak($this);
    }
}
