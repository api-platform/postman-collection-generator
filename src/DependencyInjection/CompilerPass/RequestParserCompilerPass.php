<?php

namespace PostmanGeneratorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RequestParserCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('postman.parser.factory');

        foreach ($container->findTaggedServiceIds('postman.request_parser') as $id => $tags) {
            $registryDefinition->addMethodCall('addRequestParser', [new Reference($id)]);
        }
    }
}
