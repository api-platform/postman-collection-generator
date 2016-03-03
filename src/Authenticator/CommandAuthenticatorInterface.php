<?php

namespace PostmanGeneratorBundle\Authenticator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandAuthenticatorInterface
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function interact(InputInterface $input, OutputInterface $output);
}
