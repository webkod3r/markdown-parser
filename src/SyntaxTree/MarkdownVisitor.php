<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

/**
 * Interface declaration of what an object implementing the visitor pattern
 * should do in order to iterate over the AST data structure.
 */
interface MarkdownVisitor
{
    public function visitDocument(HtmlDocument $node): string;

    public function visitHeading(HtmlHeader $node): string;

    public function visitParagraph(HtmlParagraph $node): string;

    public function visitAnchor(HtmlAnchor $node): string;

    public function visitText(HtmlText $node): string;

    public function visitLineBreak(HtmlLineBreak $node): string;
}
