<?php 

declare(strict_types=1);

namespace MarkdownParser\CodeGenerator;

use MarkdownParser\SyntaxTree\HtmlAnchor;
use MarkdownParser\SyntaxTree\HtmlDocument;
use MarkdownParser\SyntaxTree\HtmlElement;
use MarkdownParser\SyntaxTree\HtmlHeader;
use MarkdownParser\SyntaxTree\HtmlLineBreak;
use MarkdownParser\SyntaxTree\HtmlParagraph;
use MarkdownParser\SyntaxTree\HtmlText;
use MarkdownParser\SyntaxTree\MarkdownVisitor;

final class MarkdownHtmlGenerator implements MarkdownVisitor
{
    /**
     * Iterate over the list of nodes and export the HTML generated
     *
     * @param HtmlDocument $doc
     * @return string
     */
    public function export(HtmlDocument $doc): string
    {
        return $this->visitDocument($doc);
    }
    
    /**
     * Implement html visitor for document and generate outter html.
     */
    public function visitDocument(HtmlDocument $node): string
    {
        $output = "";
        $qty = count($node->children());

        /** @var HtmlElement $child */
        foreach ($node->children() as $index => $child) {
            // run visitor over child
            $output .= $child->accept($this);
            if ($index + 1 < $qty) {
                // since we are generating non-optimized html
                // and following the assignment example
                // we need to add a line-break for each parent node
                $output .= "\n\n";
            }
        }

        return $output;
    }
    
    /**
     * Implementing visitor over header node
     */
    public function visitHeading(HtmlHeader $node): string
    {
        $headerContent = '';
        foreach ($node->children() as $child) {
            $headerContent .= $child->accept($this);
        }
        
        return sprintf(
            '<%1$s>%2$s</%1$s>',
            $node->tag(),
            $headerContent
        );
    }

    /**
     * Implementing visitor over paragraph node
     */
    public function visitParagraph(HtmlParagraph $node): string
    {
        $output = "";
        foreach ($node->children() as $index => $child) {
            $output .= $child->accept($this);
        }

        return sprintf(
            '<%1$s>%2$s</%1$s>',
            $node->tag(),
            $output
        );
    }

    /**
     * Generates HTML from anchor node
     */
    public function visitAnchor(HtmlAnchor $node): string
    {
        $textNode = '';
        foreach ($node->children() as $child) {
            $textNode .= $child->accept($this);
        }

        return sprintf(
            '<%1$s href="%2$s">%3$s</%1$s>', 
            $node->tag(),
            $node->link(),
            $textNode
        );
    }

    /**
     * Process the plain text in node.
     */
    public function visitText(HtmlText $node): string
    {
        return $node->text();
    }

    /**
     * Generates a line break element string
     */
    public function visitLineBreak(HtmlLineBreak $node): string
    {
        // return sprintf('<%s/>', $node->tag());
        return "\n";
    }
}
