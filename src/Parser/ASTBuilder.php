<?php

declare(strict_types=1);

namespace MarkdownParser\Parser;

use MarkdownParser\SyntaxTree\HtmlDocument;
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
