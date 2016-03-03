<?php

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
