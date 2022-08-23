<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

class HtmlAnchor extends HtmlContainerElement
{
    /**
     * Ancho URI link
     *
     * @var string
     */
    private string $link;

    public function __construct(string $link)
    {
        $this->tag = 'a';
        $this->children = [];
        $this->link = $link;
    }

    /**
     * URI getter
     *
     * @return string
     */
    public function link(): string
    {
        return $this->link;
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitAnchor($this);
    }
}
