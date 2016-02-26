<?php

namespace PostmanGeneratorBundle\Registry;

use Dunglas\ApiBundle\Api\ResourceInterface;

class ResourceRegistry
{
    /**
     * @var ResourceInterface[]
     */
    private $resources = [];

    /**
     * @return ResourceInterface[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
//        $entityClass = $resource->getEntityClass();
//        $this->configuration[$entityClass] = [];
//        foreach ($resource->getCollectionOperations() as $operation) {
//            $this->configuration[$entityClass][] = [
//                'method' => $operation->getRoute()->getMethods()
//            ];
//        }
//            [
//                'method' => 'GET',
//                'url' => '/companies',
//                'name' => 'Get companies',
//            ],
//            [
//                'method' => 'GET',
//                'url' => '/companies?name=foo',
//                'name' => 'Search companies',
//            ],
//            [
//                'method' => 'GET',
//                'url' => '/companies/1',
//                'name' => 'Get company',
//            ],
//            [
//                'method' => 'POST',
//                'url' => '/companies',
//                'name' => 'Create a company',
//            ],
//            [
//                'method' => 'PUT',
//                'url' => '/companies/1',
//                'name' => 'Update a company',
//            ],
//            [
//                'method' => 'DELETE',
//                'url' => '/companies/1',
//                'name' => 'Delete a company',
//            ],
//            [
//                'method' => 'GET',
//                'url' => '/companies/legacy/1',
//                'name' => 'Get company by legacyId',
//            ],
//        ];
    }
}
