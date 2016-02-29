<?php

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

class RequestParserFactory
{
    /**
     * @var RequestParserInterface[]
     */
    private $parsers = [];

    /**
     * @param RequestParserInterface $parser
     */
    public function addRequestParser(RequestParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @param Request $request
     */
    public function parse(Request $request)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($request)) {
                $parser->parse($request);
            }
        }
    }
}
