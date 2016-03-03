<?php

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

class UriRequestParser implements RequestParserInterface
{
    const PATTERN = '/\/({id})/';

    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        $request->setUrl(preg_replace(self::PATTERN, '/1', $request->getUrl()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return preg_match(self::PATTERN, $request->getUrl());
    }
}
