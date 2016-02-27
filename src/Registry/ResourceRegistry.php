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
    }
}
