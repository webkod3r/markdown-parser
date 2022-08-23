<?php

declare(strict_types=1);

namespace Tests\Parser;

use MarkdownParser\CodeGenerator\MarkdownHtmlGenerator;
use MarkdownParser\Parser\ASTBuilder;
use PHPUnit\Framework\TestCase as BaseTestCase;

class ASTBuilderTest extends BaseTestCase
{
    /**
     * @var ASTBuilder
     */
    private $builder;

    /**
     * @var MarkdownHtmlGenerator
     */
    private $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new ASTBuilder();
        $this->generator = new MarkdownHtmlGenerator();
    }

    /**
     * @dataProvider markdownDataProvider
     */
    public function testConvert(string $input, string $expected): void
    {
        $this->builder->build($input);
        $output = $this->generator->export($this->builder->getAST());
        $this->assertEquals(
            $expected,
            $output
        );
    }

    public function markdownDataProvider(): array
    {
        return [
            // test-case
            'just a paragraph' => [
                'input' => <<<'EOT'
This is just a text
EOT,
                'expected' => <<<'EOT'
<p>This is just a text</p>
EOT,
            ],

            // test-case
            'multi-line paragraphs' => [
                'input' => <<<EOT
Paragraph line 1.
Also line 1
EOT,
                'expected' => <<<EOT
<p>Paragraph line 1.
Also line 1</p>
EOT,
            ],

            // test-case
            'just a link' => [
                'input' => <<<EOT
[with an inline link](http://google.com)
EOT,
                'expected' => <<<EOT
<p><a href="http://google.com">with an inline link</a></p>
EOT,
            ],

            // test-case
            'multi-line paragraph with link' => [
                'input' => <<<EOT
This is a paragraph [with an inline link](http://google.com)[second link empty](). Neat, eh?
EOT,
                'expected' => <<<EOT
<p>This is a paragraph <a href="http://google.com">with an inline link</a><a href="">second link empty</a>. Neat, eh?</p>
EOT,
            ],

            // test-case
            'multiple levels and elements' => [
                'input' => <<<EOT
# Header one

Hello there

How are you?
What's going on?

## Another Header

This is a paragraph [with an inline link](http://google.com). Neat, eh?

## This is a header [with a link](http://yahoo.com)

####### Seven hashes don't make a title
EOT,
                'expected' => <<<EOT
<h1>Header one</h1>

<p>Hello there</p>

<p>How are you?
What's going on?</p>

<h2>Another Header</h2>

<p>This is a paragraph <a href="http://google.com">with an inline link</a>. Neat, eh?</p>

<h2>This is a header <a href="http://yahoo.com">with a link</a></h2>

<p>####### Seven hashes don't make a title</p>
EOT,
            ],

            //
            // test-case
            'empty strings' => [
                'input' => '',
                'expected' => '',
            ],
        ];
    }
}