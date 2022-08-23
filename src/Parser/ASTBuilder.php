<?php

declare(strict_types=1);

namespace MarkdownParser\Parser;

use MarkdownParser\SyntaxTree\HtmlAnchor;
use MarkdownParser\SyntaxTree\HtmlDocument;
use MarkdownParser\SyntaxTree\HtmlHeader;
use MarkdownParser\SyntaxTree\HtmlLineBreak;
use MarkdownParser\SyntaxTree\HtmlParagraph;
use MarkdownParser\SyntaxTree\HtmlText;
use MarkdownParser\SyntaxTree\Nestable;

class ASTBuilder
{
    /**
     * HTML document node
     *
     * @var HtmlDocument
     */
    private $document;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new HtmlDocument();
    }

    /**
     * Getter for build AST document
     *
     * @return HtmlDocument
     */
    public function getAST(): HtmlDocument
    {
        return $this->document;
    }

    /**
     * Build a document from given input
     *
     * @param string $input
     * @return void
     */
    public function build(string $input): void
    {
        $this->document = new HtmlDocument();
        $lines = $this->getLines($input);
        $headerRegExp = '/^(\#+)([[:blank:]].+)+/';
        $processingP = false;
        /** @var HtmlParagraph|null */
        $paragraph = null;

        foreach ($lines as $line) {
            $matches = [];
            // check if header
            if (preg_match($headerRegExp, $line, $matches)) {
                $hashes = $matches[1];
                // if valid header level process node
                if (strlen($hashes) <= 6) {
                    if ($processingP) {
                        $processingP = false;
                        $this->document->appendNode($paragraph);
                    }

                    // remove the first space in text which is required by MD syntax
                    $content = substr($matches[2], 1);
                    $headerNode = new HtmlHeader(strlen($hashes));
                    $this->document->appendNode($headerNode);
                    // process content inside header
                    $this->processLine($content, $headerNode);
                    continue;
                }
            }

            $trimmedLine = trim($line);
            if (
                (empty($trimmedLine) && count($this->document->children()) === 0) ||
                (!$processingP && empty($trimmedLine))
            ) {
                continue;
            }

            // if processing paragraph but new line is empty, then
            // act accordingly and close the paragraph and insert a new 
            // line break
            if ($processingP && empty($trimmedLine)) {
                $processingP = false;
                $this->document->appendNode($paragraph);
                continue;
            }

            if ($processingP) {
                // if a p is in the making and we are processing another line, then add a break-line
                $paragraph->appendNode(new HtmlLineBreak());
            } else {
                $processingP = true;
                $paragraph = new HtmlParagraph();
            }
            $this->processLine($line, $paragraph);
        }

        // once every line is processed, check if another paragraph was being built
        // and append it to current document
        if ($processingP) {
            $this->document->appendNode($paragraph);
        }
    }

    /**
     * Process inline elements in document and append generated node into current node
     */
    private function processLine(string $text, Nestable &$node): void
    {
        // $linkRegExp = '/^\[((?:[^][]+|(?R))*)\]\(((?:[^)(]+|(?R))*)\)/';
        $linkRegExp = '/^\[((?:[^][]+)*)\]\(((?:[^)(]+)*)\)/';

        $excerpt = '';
        $pos = 0;
        while ($pos < strlen($text)) {
            $char = $text[$pos];
            switch ($char) {
                case '[':
                    // test if link
                    $reminder = substr($text, $pos);
                    $matches = [];
                    if (preg_match($linkRegExp, $reminder, $matches)) {
                        // before processing the link, insert captured text into a text node
                        $textNode = new HtmlText($excerpt);
                        $node->appendNode($textNode);
                        $excerpt = '';

                        // matched link
                        $link = $matches[2];
                        $anchorNode = new HtmlAnchor($link);
                        // add text node from match 1
                        $anchorNode->appendNode(new HtmlText($matches[1]));
                        $node->appendNode($anchorNode);
                        // move carret position at the end of matched string
                        $pos += strlen($matches[0]);

                        // process reminder text in line
                        $reminder = substr($text, $pos);
                        $this->processLine($reminder, $node);
                        return;
                    }
                default:
                    // consume character
                    $excerpt .= $char;
                    $pos++;
                    // test if reached end of line
                    if ($pos === strlen($text)) {
                        // insert the captured content into a text node
                        $textNode = new HtmlText($excerpt);
                        $node->appendNode($textNode);
                        $excerpt = '';
                    }
                    break;
            }
        }
    }

    /**
     * Returns input source by lines
     *
     * @param string $input
     * @return array
     */
    private function getLines(string $input): array
    {
        return preg_split('/\n/', $input);
    }
}
