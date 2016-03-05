<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
