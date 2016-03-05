<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestParser;

use PostmanGeneratorBundle\RequestParser\NameRequestParser;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class NameRequestParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NameRequestParser
     */
    private $parser;

    /**
     * @var ObjectProphecy
     */
    private $requestMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->requestMock = $this->prophesize('PostmanGeneratorBundle\Model\Request');

        $this->parser = new NameRequestParser();
    }

    public function testSupports()
    {
        $this->requestMock->getName()->shouldBeCalledTimes(1);

        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
    }

    public function testNoSupports()
    {
        $this->requestMock->getName()->willReturn('foo')->shouldBeCalledTimes(1);

        $this->assertFalse($this->parser->supports($this->requestMock->reveal()));
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param string $method
     * @param string $name
     * @param string $url
     */
    public function testParse($method, $name, $url = null)
    {
        $resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');

        $this->requestMock->getResource()->willReturn($resourceMock->reveal())->shouldBeCalledTimes(1);
        $resourceMock->getShortName()->willReturn('User')->shouldBeCalledTimes(1);
        $this->requestMock->getMethod()->willReturn($method)->shouldBeCalledTimes(1);
        $this->requestMock->setName($name)->shouldBeCalledTimes(1);
        if ('GET' === $method) {
            $this->requestMock->getUrl()->willReturn($url)->shouldBeCalledTimes(1);
        }

        $this->parser->parse($this->requestMock->reveal());
    }

    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            ['POST', 'Create user'],
            ['PUT', 'Update user'],
            ['PATCH', 'Update user'],
            ['DELETE', 'Delete user'],
            ['GET', 'Get user', '/users/{id}'],
            ['GET', 'Get users list', '/users'],
        ];
    }
}
