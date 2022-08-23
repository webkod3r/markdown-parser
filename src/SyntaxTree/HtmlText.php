<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

class HtmlText extends HtmlElement
{
    /**
     * Node text
     *
     * @var string
     */
    private string $text;

    public function __construct(string $text)
    {
        // plain text have no tag
        $this->tag = '';
        $this->text = $text;
    }

    /**
     * String contained in node
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitText($this);
    }
}
