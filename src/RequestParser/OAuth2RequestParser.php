<?php

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

class OAuth2RequestParser implements RequestParserInterface
{
    /**
     * @var string
     */
    private $authentication;

    /**
     * @param string $authentication
     */
    public function __construct($authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        $request->addHeader('Authorization', 'Bearer {{oauth2_access_token}}');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return 'oauth2' === strtolower($this->authentication);
    }
}
