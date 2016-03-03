<?php

namespace PostmanGeneratorBundle;

use PostmanGeneratorBundle\DependencyInjection\CompilerPass\CommandParserCompilerPass;
use PostmanGeneratorBundle\DependencyInjection\CompilerPass\RequestParserCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PostmanGeneratorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RequestParserCompilerPass());
        $container->addCompilerPass(new CommandParserCompilerPass());
    }
}
