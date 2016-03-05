<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Generator;

use PostmanGeneratorBundle\Generator\RequestGenerator;
use Prophecy\Argument;

class RequestGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $requestParserChainMock = $this->prophesize('PostmanGeneratorBundle\RequestParser\RequestParserChain');
        $resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');
        $operationMock = $this->prophesize('Dunglas\ApiBundle\Api\Operation\OperationInterface');
        $routeMock = $this->prophesize('Symfony\Component\Routing\Route');

        $resourceMock->getCollectionOperations()->willReturn([
            $operationMock->reveal(),
            $operationMock->reveal(),
            $operationMock->reveal(),
        ])->shouldBeCalledTimes(1);
        $resourceMock->getItemOperations()->willReturn([
            $operationMock->reveal(),
            $operationMock->reveal(),
            $operationMock->reveal(),
        ])->shouldBeCalledTimes(1);
        $operationMock->getRoute()->willReturn($routeMock->reveal())->shouldBeCalledTimes(6);
        $routeMock->getMethods()
            ->willReturn(['GET'], ['POST'], ['GET'], ['GET'], ['PUT', 'PATCH'], ['DELETE'])
            ->shouldBeCalledTimes(6);
        $routeMock->getPath()
            ->willReturn('/users', '/users', '/profile', '/users/{id}', '/users/{id}', '/users/{id}', '/users/{id}')
            ->shouldBeCalledTimes(7);
        $operationMock->getContext()
            ->willReturn([], [], ['hydra:title' => 'Get profile'], ['hydra:title' => 'Get profile'], [], [], [], [])
            ->shouldBeCalledTimes(8);
        $requestParserChainMock->parse(Argument::type('PostmanGeneratorBundle\Model\Request'))->shouldBeCalledTimes(7);

        $generator = new RequestGenerator($requestParserChainMock->reveal(), 'http://127.0.0.1');
        $requests = $generator->generate($resourceMock->reveal());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[0]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[0]->getResource());
        $this->assertEquals('http://127.0.0.1/users', $requests[0]->getUrl());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertNull($requests[0]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[1]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[1]->getResource());
        $this->assertEquals('http://127.0.0.1/users', $requests[1]->getUrl());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertNull($requests[1]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[2]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[2]->getResource());
        $this->assertEquals('http://127.0.0.1/profile', $requests[2]->getUrl());
        $this->assertEquals('GET', $requests[2]->getMethod());
        $this->assertEquals('Get profile', $requests[2]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[3]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[3]->getResource());
        $this->assertEquals('http://127.0.0.1/users/{id}', $requests[3]->getUrl());
        $this->assertEquals('GET', $requests[3]->getMethod());
        $this->assertNull($requests[3]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[4]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[4]->getResource());
        $this->assertEquals('http://127.0.0.1/users/{id}', $requests[4]->getUrl());
        $this->assertEquals('PUT', $requests[4]->getMethod());
        $this->assertNull($requests[4]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[5]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[5]->getResource());
        $this->assertEquals('http://127.0.0.1/users/{id}', $requests[5]->getUrl());
        $this->assertEquals('PATCH', $requests[5]->getMethod());
        $this->assertNull($requests[5]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[6]->getId());
        $this->assertEquals($resourceMock->reveal(), $requests[6]->getResource());
        $this->assertEquals('http://127.0.0.1/users/{id}', $requests[6]->getUrl());
        $this->assertEquals('DELETE', $requests[6]->getMethod());
        $this->assertNull($requests[6]->getName());
    }
}
