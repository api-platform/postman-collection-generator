<?php

namespace PostmanGeneratorBundle\Authenticator;

use PostmanGeneratorBundle\Model\Request;

class JwtAuthenticator implements AuthenticatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(Request $request)
    {
        throw new \Exception('This authenticator is not implemented yet.');
    }
}
