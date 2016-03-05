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

use PostmanGeneratorBundle\RequestParser\RequestParserChain;
use Prophecy\Prophecy\ObjectProphecy;

class RequestParserChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $requestMock;

    /**
     * @var ObjectProphecy
     */
    private $requestParserMock;

    /**
     * @var RequestParserChain
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->requestMock = $this->prophesize('PostmanGeneratorBundle\Model\Request');
        $this->requestParserMock = $this->prophesize('PostmanGeneratorBundle\RequestParser\RequestParserInterface');

        $this->parser = new RequestParserChain([
            $this->requestParserMock->reveal(),
            $this->requestParserMock->reveal(),
        ]);
    }

    public function testSupports()
    {
        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
    }

    public function testParse()
    {
        $this->requestParserMock->supports($this->requestMock->reveal())
            ->willReturn(true, false)
            ->shouldBeCalledTimes(2);
        $this->requestParserMock->parse($this->requestMock->reveal())->shouldBeCalledTimes(1);

        $this->parser->parse($this->requestMock->reveal());
    }
}
