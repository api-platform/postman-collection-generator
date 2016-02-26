<?php

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\Operation\OperationInterface;
use Dunglas\ApiBundle\Api\ResourceInterface;

class FolderGenerator
{
    /**
     * @param ResourceInterface $resource
     * @param int               $collectionId
     *
     * @return array
     */
    public function generate(ResourceInterface $resource, $collectionId)
    {
        $folder = [
            'id' => md5($resource->getEntityClass()),
            'name' => $resource->getShortName(),
            'owner' => 0,
            'order' => [],
            'collectionId' => $collectionId,
        ];

        /** @var OperationInterface[] $operations */
        $operations = array_merge($resource->getItemOperations(), $resource->getCollectionOperations());
        foreach ($operations as $operation) {
            foreach ($operation->getRoute()->getMethods() as $method) {
                $folder['order'][] = md5(sprintf('%s %s', $method, $operation->getRoute()->getPath()));
            }
        }

        return $folder;
    }
}
