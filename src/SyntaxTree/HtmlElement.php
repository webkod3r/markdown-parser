<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

abstract class HtmlElement
{
    /**
     * HTML tag representing the element
     */
    protected string $tag;

    /**
     * HTML tag getter
     */
    public function tag(): string
    {
        return $this->tag;
    }

    /**
     * Accept visitor processing, isolating processing logic from class. Required for visitor design pattern
     *
     * @param MarkdownVisitor $visitor
     * @return string
     */
    public abstract function accept(MarkdownVisitor $visitor): string;
}
