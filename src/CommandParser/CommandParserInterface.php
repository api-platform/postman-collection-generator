<?php

namespace PostmanGeneratorBundle\CommandParser;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandParserInterface
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function parse(InputInterface $input, OutputInterface $output);

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output);

    /**
     * @return bool
     */
    public function supports();
}
