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

use PostmanGeneratorBundle\RequestParser\UriRequestParser;
use Prophecy\Prophecy\ObjectProphecy;

class UriRequestParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $requestMock;

    /**
     * @var UriRequestParser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->requestMock = $this->prophesize('PostmanGeneratorBundle\Model\Request');

        $this->parser = new UriRequestParser();
    }

    public function testSupports()
    {
        $this->requestMock->getUrl()->willReturn('/users/{id}')->shouldBeCalledTimes(1);

        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
    }

    public function testNoSupports()
    {
        $this->requestMock->getUrl()->willReturn('/users')->shouldBeCalledTimes(1);

        $this->assertFalse($this->parser->supports($this->requestMock->reveal()));
    }

    public function testParse()
    {
        $this->requestMock->getUrl()->willReturn('/users/{id}')->shouldBeCalledTimes(1);
        $this->requestMock->setUrl('/users/1')->shouldBeCalledTimes(1);

        $this->parser->parse($this->requestMock->reveal());
    }
}
