<?php

namespace PostmanGeneratorBundle\Authenticator;

use PostmanGeneratorBundle\Model\Request;

class OAuth2Authenticator implements AuthenticatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(Request $request)
    {
        $request->addHeader('Authorization', 'Bearer access-token-michiel');
    }
}
