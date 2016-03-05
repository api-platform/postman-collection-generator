<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
