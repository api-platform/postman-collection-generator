<?php

/*
 * This file is part of the postman-collection-generator package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PostmanGeneratorBundle\DependencyInjection\CompilerPass\CommandParserCompilerPass;
use PostmanGeneratorBundle\DependencyInjection\CompilerPass\RequestParserCompilerPass;
use PostmanGeneratorBundle\PostmanGeneratorBundle;

class PostmanGeneratorBundleTest extends PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $containerMock = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerMock->addCompilerPass(new RequestParserCompilerPass())->shouldBeCalledTimes(1);
        $containerMock->addCompilerPass(new CommandParserCompilerPass())->shouldBeCalledTimes(1);

        $bundle = new PostmanGeneratorBundle();
        $bundle->build($containerMock->reveal());
    }
}
