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
use PostmanGeneratorBundle\Model\Test;

class TestRequestParser implements RequestParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        switch ($request->getMethod()) {
            case 'POST':
                $request->addTest(new Test('Successful POST request', 'responseCode.code === 201 || responseCode.code === 202'));
                $request->addTest(new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"'));
                break;
            case 'PUT':
            case 'PATCH':
            case 'GET':
                $request->addTest(new Test(sprintf('Successful %s request', $request->getMethod()), 'responseCode.code === 200'));
                $request->addTest(new Test('Content-Type is correct', 'postman.getResponseHeader("Content-Type") === "application/ld+json"'));
                break;
            case 'DELETE':
                $request->addTest(new Test('Successful DELETE request', 'responseCode.code === 204'));
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return true;
    }
}
