<?php

namespace PostmanGeneratorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AuthenticatorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('postman.generator.authentication');

        foreach ($container->findTaggedServiceIds('postman.authenticator') as $id => $tags) {
            $registryDefinition->addMethodCall('addAuthenticator', [$tags[0]['alias'], new Reference($id)]);
        }
    }
}
