<?php

declare(strict_types=1);

namespace MarkdownParser\SyntaxTree;

use InvalidArgumentException;

class HtmlHeader extends HtmlContainerElement
{
    /**
     * Header level from 1-6
     *
     * @var integer
     */
    private int $level;

    /**
     * Creates a heading object representation
     *
     * @param integer $level HTML header level
     */
    public function __construct(int $level)
    {
        if ($level > 6 || $level < 1) {
            throw new InvalidArgumentException('Invalid heading tag level');
        }
        $this->tag = 'h' . $level;
        $this->level = $level;
        $this->children = [];
    }

    /**
     * Header level
     *
     * @return integer
     */
    public function level(): int
    {
        return $this->level;
    }

    /**
     * Accepts the visitor to process node
     */
    public function accept(MarkdownVisitor $visitor): string
    {
        return $visitor->visitHeading($this);
    }
}
