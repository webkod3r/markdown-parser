<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

abstract class HtmlContainerElement extends HtmlElement implements Nestable
{
    /**
     * List of nodes contained in current node
     *
     * @var HtmlElement[]
     */
    private array $children;
    
    /**
     * List of nodes contained in current node
     *
     * @return HtmlElement[]
     */
    public function children(): array
    {
        return $this->children;
    }

    /**
     * Append node into current element
     *
     * @param HtmlElement $node
     * @return void
     */
    public function appendNode(HtmlElement $node): void
    {
        $this->children[] = $node;
    }
}
