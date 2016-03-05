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

use PostmanGeneratorBundle\Model\Test;
use PostmanGeneratorBundle\RequestParser\TestRequestParser;
use Prophecy\Prophecy\ObjectProphecy;

class TestRequestParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $requestMock;

    /**
     * @var TestRequestParser
     */
    private $parser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->requestMock = $this->prophesize('PostmanGeneratorBundle\Model\Request');
        
        $this->parser = new TestRequestParser();
    }

    public function testSupports()
    {
        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
    }

    /**
     * @dataProvider getDataProvider
     *
     * @param string $method
     * @param array  $tests
     */
    public function testParse($method, array $tests)
    {
        $this->requestMock->getMethod()->willReturn($method)->shouldBeCalled();
        foreach ($tests as $test) {
            $this->requestMock->addTest($test)->shouldBeCalledTimes(1);
        }

        $this->parser->parse($this->requestMock->reveal());
    }

    public function getDataProvider()
    {
        return [
            [
                'POST',
                [
                    new Test('Successful POST request', 'responseCode.code === 201 || responseCode.code === 202'),
                    new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"')
                ],
            ],
            [
                'PUT',
                [
                    new Test('Successful PUT request', 'responseCode.code === 200'),
                    new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"'),
                ],
            ],
            [
                'PATCH',
                [
                    new Test('Successful PATCH request', 'responseCode.code === 200'),
                    new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"'),
                ],
            ],
            [
                'GET',
                [
                    new Test('Successful GET request', 'responseCode.code === 200'),
                    new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"'),
                ],
            ],
            [
                'DELETE',
                [
                    new Test('Successful DELETE request', 'responseCode.code === 204')
                ],
            ],
        ];
    }
}
