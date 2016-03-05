<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
