<?php

namespace PostmanGeneratorBundle\Generator;

use Doctrine\Common\Inflector\Inflector;
use Dunglas\ApiBundle\Api\Operation\OperationInterface;
use Dunglas\ApiBundle\Api\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(ResourceInterface $resource, $baseUrl)
    {
        /** @var OperationInterface[] $operations */
        $operations = array_merge($resource->getCollectionOperations(), $resource->getItemOperations());
        $requests = [];

        foreach ($operations as $operation) {
            foreach ($operation->getRoute()->getMethods() as $method) {
                $isCollection = 'DunglasApiBundle:Resource:cget' === $operation->getRoute()->getDefault('_controller');
                $name = $this->generateDefaultName($method, $resource->getShortName(), $isCollection);
                if (isset($operation->getContext()['hydra:title'])) {
                    $name = $operation->getContext()['hydra:title'];
                }

                // Default data
                $request = [
                    'id' => md5(sprintf('%s %s', $method, $operation->getRoute()->getPath())),
                    'url' => $this->generateUrl($baseUrl, $operation->getRoute()->getPath()),
                    'method' => $method,
                    'tests' => [], // @todo add tests
                    'folder' => md5($resource->getEntityClass()),
                    'name' => $name,
                    'description' => '',
                    'preRequestScript' => '',
                    'pathVariables' => new \stdClass(),
                    'data' => [],
                    'dataMode' => 'params',
                    'version' => 2,
                    'currentHelper' => 'normal',
                    'helperAttributes' => new \stdClass(),
                    'time' => time(),
//                    'isFromCollection' => true,
//                    'collectionRequestId' => md5(sprintf('%s %s', $method, $operation->getRoute()->getPath())),
                ];

                // Authentication
                // @todo How to manage request authentication ? (OAuth2, OAuth1, JWT, custom)
                // @todo Careful with encoding: double quotes only because of \n
                $request['headers'] = "Authorization: Bearer access-token-michiel\n";

                if (in_array($method, [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH])) {
                    if (!isset($request['headers'])) {
                        $request['headers'] = '';
                    }
                    $request['headers'].= "Content-Type: application/json\n";
                    $request['dataMode'] = 'raw';
                    $request['rawModeData'] = [
                        // @todo Generate data from entity (cf. $resource->getDenormalizationContext())
                        'foo' => 'bar',
                    ];
                }

                $requests[] = $request;
            }
        }

        return $requests;
    }

    /**
     * @param string $baseUrl
     * @param string $url
     *
     * @return string
     */
    private function generateUrl($baseUrl, $url)
    {
        return rtrim($baseUrl, '/').str_ireplace('{id}', 1, $url);
    }

    /**
     * @param string $method
     * @param string $name
     * @param bool   $isCollection
     *
     * @return string
     */
    private function generateDefaultName($method, $name, $isCollection = true)
    {
        switch ($method) {
            case Request::METHOD_POST:
                return sprintf('Create %s', Inflector::camelize($name));
            case Request::METHOD_PUT:
            case Request::METHOD_PATCH:
                return sprintf('Update %s', Inflector::camelize($name));
            case Request::METHOD_DELETE:
                return sprintf('Delete %s', Inflector::camelize($name));
            case Request::METHOD_GET:
                if ($isCollection) {
                    return sprintf('Get %s list', Inflector::pluralize(Inflector::camelize($name)));
                }

                return sprintf('Get %s', Inflector::camelize($name));
        }
    }
}
