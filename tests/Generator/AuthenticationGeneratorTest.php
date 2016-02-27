<?php

namespace Generator;

use PostmanGeneratorBundle\Generator\AuthenticationGenerator;

class AuthenticationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAuthenticator()
    {
        $authenticatorMock = $this->prophesize('PostmanGeneratorBundle\Authenticator\AuthenticatorInterface');

        $generator = new AuthenticationGenerator();
        $generator->addAuthenticator('foo', $authenticatorMock->reveal());

        $this->assertEquals($authenticatorMock->reveal(), $generator->get('foo'));
    }
}
