<?php

namespace PostmanGeneratorBundle\Generator;

use PostmanGeneratorBundle\Authenticator\AuthenticatorInterface;
use PostmanGeneratorBundle\Authenticator\CommandAuthenticatorInterface;

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

    /**
     * @return CommandAuthenticatorInterface[]
     */
    public function getCommandAuthenticators()
    {
        return array_filter($this->authenticators, function (AuthenticatorInterface $authenticator) {
            return $authenticator instanceof CommandAuthenticatorInterface;
        });
    }
}
