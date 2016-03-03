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
        $registryDefinition = $container->getDefinition('postman.request_parser.chain');

        $requestParsers = [];
        foreach ($container->findTaggedServiceIds('postman.request_parser') as $serviceId => $tags) {
            $attributes = $container->getDefinition($serviceId)->getTag('postman.request_parser');
            $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;
            $requestParsers[$priority][] = new Reference($serviceId);
        }
        krsort($requestParsers);

        $registryDefinition->replaceArgument(0, call_user_func_array('array_merge', $requestParsers));
    }
}
