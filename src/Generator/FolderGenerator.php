<?php

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\ResourceInterface;
use PostmanGeneratorBundle\Model\Folder;

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
        $folder->setId(md5($resource->getEntityClass()));
        $folder->setName($resource->getShortName());

        return $folder;
    }
}
