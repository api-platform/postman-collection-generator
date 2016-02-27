<?php

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\ResourceInterface;
use PostmanGeneratorBundle\Model\Folder;
use Ramsey\Uuid\Uuid;

class FolderGenerator implements GeneratorInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Folder
     */
    public function generate(ResourceInterface $resource = null)
    {
        $folder = new Folder();
        $folder->setId((string)Uuid::uuid4());
        $folder->setName($resource->getShortName());

        return $folder;
    }
}
