<?php

namespace PostmanGeneratorBundle\Authenticator;

use PostmanGeneratorBundle\Model\Request;

interface AuthenticatorInterface
{
    /**
     * @param Request $request
     */
    public function generate(Request $request);
}
