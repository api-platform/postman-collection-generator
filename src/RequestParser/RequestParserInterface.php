<?php

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

interface RequestParserInterface
{
    /**
     * @param Request $request
     */
    public function parse(Request $request);

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request);
}
