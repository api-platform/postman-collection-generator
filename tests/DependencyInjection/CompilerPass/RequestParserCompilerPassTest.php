<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DependencyInjection\CompilerPass;

use PostmanGeneratorBundle\DependencyInjection\CompilerPass\RequestParserCompilerPass;
use Symfony\Component\DependencyInjection\Reference;

class RequestParserCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $containerMock = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definitionMock = $this->prophesize('Symfony\Component\DependencyInjection\Definition');

        $containerMock->getDefinition('postman.request_parser.chain')
            ->willReturn($definitionMock->reveal())
            ->shouldBeCalledTimes(1);
        $containerMock->findTaggedServiceIds('postman.request_parser')
            ->willReturn([
                'postman.request_parser.foo' => [['name' => 'postman.request_parser', 'priority' => 0]],
                'postman.request_parser.bar' => [['name' => 'postman.request_parser', 'priority' => 1]],
            ])
            ->shouldBeCalledTimes(1);
        $containerMock->getDefinition('postman.request_parser.foo')
            ->willReturn($definitionMock->reveal())
            ->shouldBeCalledTimes(1);
        $containerMock->getDefinition('postman.request_parser.bar')
            ->willReturn($definitionMock->reveal())
            ->shouldBeCalledTimes(1);
        $definitionMock->getTag('postman.request_parser')->willReturn(
            [['name' => 'postman.request_parser', 'priority' => 0]],
            [['name' => 'postman.request_parser', 'priority' => 1]]
        )->shouldBeCalledTimes(2);

        $definitionMock->replaceArgument(0, [
            new Reference('postman.request_parser.bar'),
            new Reference('postman.request_parser.foo'),
        ])->shouldBeCalledTimes(1);

        $compilerPass = new RequestParserCompilerPass();
        $compilerPass->process($containerMock->reveal());
    }
}
