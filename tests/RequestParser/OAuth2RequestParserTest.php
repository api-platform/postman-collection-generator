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

use PostmanGeneratorBundle\RequestParser\OAuth2RequestParser;
use Prophecy\Prophecy\ObjectProphecy;

class OAuth2RequestParserTest extends \PHPUnit_Framework_TestCase
{
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
    }

    public function testSupports()
    {
        $parser = new OAuth2RequestParser('oauth2');
        $this->assertTrue($parser->supports($this->requestMock->reveal()));
    }

    public function testNoSupports()
    {
        $parser = new OAuth2RequestParser('oauth1');
        $this->assertFalse($parser->supports($this->requestMock->reveal()));
    }

    public function testParse()
    {
        $this->requestMock->addHeader('Authorization', 'Bearer {{oauth2_access_token}}')->shouldBeCalledTimes(1);

        $parser = new OAuth2RequestParser('oauth2');
        $parser->parse($this->requestMock->reveal());
    }
}
