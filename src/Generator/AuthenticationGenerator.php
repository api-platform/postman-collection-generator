<?php

namespace PostmanGeneratorBundle\Generator;

use PostmanGeneratorBundle\Authenticator\AuthenticatorInterface;

class AuthenticationGenerator
{
    /**
     * @var AuthenticatorInterface[]
     */
    private $authenticators = [];

    /**
     * @param string                 $alias
     * @param AuthenticatorInterface $authenticator
     */
    public function addAuthenticator($alias, AuthenticatorInterface $authenticator)
    {
        $this->authenticators[$alias] = $authenticator;
    }

    /**
     * @param string $alias
     *
     * @return AuthenticatorInterface
     */
    public function get($alias)
    {
        return $this->authenticators[$alias];
    }
}
