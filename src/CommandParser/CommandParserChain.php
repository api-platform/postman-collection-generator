<?php

namespace PostmanGeneratorBundle\CommandParser;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandParserChain implements CommandParserInterface
{
    /**
     * @var CommandParserInterface[]
     */
    private $parsers = [];

    /**
     * @param CommandParserInterface $parser
     */
    public function addCommandParser(CommandParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports()) {
                $parser->parse($input, $output);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports()) {
                $parser->execute($input, $output);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports()
    {
        return true;
    }
}
