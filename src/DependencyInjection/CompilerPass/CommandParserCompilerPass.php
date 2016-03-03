<?php

namespace PostmanGeneratorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommandParserCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('postman.command_parser.chain');

        $commandParsers = [];
        foreach ($container->findTaggedServiceIds('postman.command_parser') as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;
                $commandParsers[$priority][] = new Reference($serviceId);
            }
        }
        krsort($commandParsers);

        foreach (call_user_func_array('array_merge', $commandParsers) as $commandParser) {
            $registryDefinition->addMethodCall('addCommandParser', [$commandParser]);
        }
    }
}
