<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

class HtmlDocument extends HtmlContainerElement
{
    /**
     * Creates an document object representation
     */
    public function __construct()
    {
        // the document root won't have any 
        $this->tag = '';
        $this->children = [];
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitDocument($this);
    }
}
