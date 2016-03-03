<?php

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

class RequestParserChain implements RequestParserInterface
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
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($request)) {
                $parser->parse($request);
            }
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
