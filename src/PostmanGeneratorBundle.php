<?php

namespace PostmanGeneratorBundle;

use PostmanGeneratorBundle\DependencyInjection\CompilerPass\AuthenticatorCompilerPass;
use PostmanGeneratorBundle\DependencyInjection\CompilerPass\RequestParserCompilerPass;
use PostmanGeneratorBundle\DependencyInjection\CompilerPass\ResourceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PostmanGeneratorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ResourceCompilerPass());
        $container->addCompilerPass(new AuthenticatorCompilerPass());
        $container->addCompilerPass(new RequestParserCompilerPass());
    }
}
