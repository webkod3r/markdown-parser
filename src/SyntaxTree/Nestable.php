<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

interface Nestable
{
    /**
     * Return the list of HTML elements contained inside the node
     *
     * @return HtmlElement[]
     */
    public function children(): array;

    /**
     * Append child to current element
     *
     * @param HtmlElement $node
     * @return void
     */
    public function appendNode(HtmlElement $node): void;
}