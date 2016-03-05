<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            $attributes = $container->getDefinition($serviceId)->getTag('postman.command_parser');
            $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;
            $commandParsers[$priority][] = new Reference($serviceId);
        }
        krsort($commandParsers);

        $registryDefinition->replaceArgument(0, call_user_func_array('array_merge', $commandParsers));
    }
}
