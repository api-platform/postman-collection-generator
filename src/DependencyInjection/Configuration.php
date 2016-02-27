<?php

namespace PostmanGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('postman_generator');

        $rootNode
            ->children()
                ->booleanNode('public')
                    ->defaultFalse()
                ->end()
                ->scalarNode('name')
                    ->isRequired()
                ->end()
                ->scalarNode('baseUrl')
                    ->isRequired()
                ->end()
                ->scalarNode('description')->defaultNull()->end()
                ->enumNode('authentication')
                    ->defaultNull()
                    ->values(['oauth2', 'jwt'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
