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

use Dunglas\ApiBundle\Api\ResourceCollectionInterface;
use Dunglas\ApiBundle\Api\ResourceInterface;
use PostmanGeneratorBundle\Model\Collection;
use Ramsey\Uuid\Uuid;

class CollectionGenerator implements GeneratorInterface
{
    /**
     * @var ResourceCollectionInterface
     */
    private $resourceCollection;

    /**
     * @var RequestGenerator
     */
    private $requestGenerator;

    /**
     * @var FolderGenerator
     */
    private $folderGenerator;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $public = false;

    /**
     * @param ResourceCollectionInterface $resourceCollection
     * @param RequestGenerator            $requestGenerator
     * @param FolderGenerator             $folderGenerator
     * @param bool                        $public
     * @param string                      $name
     * @param string                      $description
     */
    public function __construct(
        ResourceCollectionInterface $resourceCollection,
        RequestGenerator $requestGenerator,
        FolderGenerator $folderGenerator,
        $public,
        $name = null,
        $description = null
    ) {
        $this->resourceCollection = $resourceCollection;
        $this->requestGenerator = $requestGenerator;
        $this->folderGenerator = $folderGenerator;
        $this->public = $public;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     *
     * @return Collection
     */
    public function generate(ResourceInterface $resource = null)
    {
        $collection = new Collection();
        $collection->setId((string) Uuid::uuid4());
        $collection->setName($this->name);
        $collection->setDescription($this->description);
        $collection->setPublic($this->public);

        foreach ($this->resourceCollection as $resource) {
            $folder = $this->folderGenerator->generate($resource);
            $folder->setRequests($this->requestGenerator->generate($resource));

            $collection->addFolder($folder);
        }

        return $collection;
    }
}
