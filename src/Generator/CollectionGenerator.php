<?php

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\ResourceInterface;
use PostmanGeneratorBundle\Model\Collection;
use PostmanGeneratorBundle\Registry\ResourceRegistry;
use Ramsey\Uuid\Uuid;

class CollectionGenerator implements GeneratorInterface
{
    /**
     * @var ResourceRegistry
     */
    private $resourceRegistry;

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
     * @param ResourceRegistry $resourceRegistry
     * @param RequestGenerator $requestGenerator
     * @param FolderGenerator  $folderGenerator
     * @param string           $name
     * @param string           $description
     * @param bool             $public
     */
    public function __construct(
        ResourceRegistry $resourceRegistry,
        RequestGenerator $requestGenerator,
        FolderGenerator $folderGenerator,
        $public,
        $name = null,
        $description = null
    ) {
        $this->resourceRegistry = $resourceRegistry;
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
        $collection->setId((string)Uuid::uuid4());
        $collection->setName($this->name);
        $collection->setDescription($this->description);
        $collection->setPublic($this->public);

        foreach ($this->resourceRegistry->getResources() as $resource) {
            $folder = $this->folderGenerator->generate($resource);
            $folder->setRequests($this->requestGenerator->generate($resource));

            $collection->addFolder($folder);
        }

        return $collection;
    }
}
