<?php

declare(strict_types=1);

namespace MarkdownParser\Command;

use MarkdownParser\CodeGenerator\MarkdownHtmlGenerator;
use MarkdownParser\Parser\ASTBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadMarkDownCommand extends Command
{
    /**
     * @var ASTBuilder
     */
    private $builder;

    /**
     * @var MarkdownHtmlGenerator
     */
    private $generator;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'app:load-markdown';

    public function __construct()
    {
        parent::__construct();
        $this->builder = new ASTBuilder();
        $this->generator = new MarkdownHtmlGenerator();
    }

    /**
     * Configure command with options
     *
     * @return void
     */
    protected function configure(): void
    {
        $demoFilePath = realpath(__DIR__ . '/../../tests/demo.md');
        $this
            ->addOption('inline', 'i', InputArgument::OPTIONAL, 'Inline markdown content')
            ->addOption('file', 'f', InputArgument::OPTIONAL, 'Load markdown from file', $demoFilePath)
        ;
    }
    
    /**
     * Commnad handler
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inlineContent = $input->getOption('inline');
        $filePath = $input->getOption('file');
        if (null === $inlineContent && null === $filePath) {
            $output->writeln('You must provide either inline content or a file with input!!!');
            return Command::INVALID;
        }

        if (null !== $inlineContent) {
            $this->processInlineContent($inlineContent, $output);
        } else {
            $this->processFile($filePath, $output);
        }

        return Command::SUCCESS;
    }

    /**
     * Process inline input
     */
    private function processInlineContent(string $content, OutputInterface $output): void
    {
        $this->builder->build($content);
        $output->writeln($this->generator->export($this->builder->getAST()));
    }

    /**
     * Process file with markdown content
     */
    private function processFile(string $filePath, OutputInterface $output): void
    {
        $fileContent = file_get_contents($filePath);
        
        $this->builder->build($fileContent);
        $output->writeln($this->generator->export($this->builder->getAST()));
    }
}
