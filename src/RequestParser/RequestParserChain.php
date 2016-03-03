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
     * @param RequestParserInterface[] $parsers
     */
    public function __construct(array $parsers)
    {
        $this->parsers = $parsers;
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
