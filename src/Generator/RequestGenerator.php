<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\Operation\OperationInterface;
use Dunglas\ApiBundle\Api\ResourceInterface;
use PostmanGeneratorBundle\Model\Request;
use PostmanGeneratorBundle\RequestParser\RequestParserChain;
use Ramsey\Uuid\Uuid;

class RequestGenerator implements GeneratorInterface
{
    /**
     * @var RequestParserChain
     */
    private $requestParser;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param RequestParserChain $requestParser
     * @param string             $baseUrl
     */
    public function __construct(RequestParserChain $requestParser, $baseUrl)
    {
        $this->requestParser = $requestParser;
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritdoc}
     *
     * @return Request[]
     */
    public function generate(ResourceInterface $resource = null)
    {
        /** @var OperationInterface[] $operations */
        $operations = array_merge($resource->getCollectionOperations(), $resource->getItemOperations());
        $requests = [];

        foreach ($operations as $operation) {
            $route = $operation->getRoute();
            foreach ($route->getMethods() as $method) {
                $request = new Request();
                $request->setResource($resource);
                $request->setId((string) Uuid::uuid4());
                $request->setUrl($this->baseUrl.$route->getPath());
                $request->setMethod($method);
                if (isset($operation->getContext()['hydra:title'])) {
                    $request->setName($operation->getContext()['hydra:title']);
                }

                $this->requestParser->parse($request);
                $requests[] = $request;
            }
        }

        return $requests;
    }
}
