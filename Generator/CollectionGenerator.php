<?php

namespace PostmanGeneratorBundle\Generator;

use PostmanGeneratorBundle\Registry\ResourceRegistry;

class CollectionGenerator
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
     * @param ResourceRegistry $resourceRegistry
     * @param RequestGenerator $requestGenerator
     * @param FolderGenerator  $folderGenerator
     */
    public function __construct(
        ResourceRegistry $resourceRegistry,
        RequestGenerator $requestGenerator,
        FolderGenerator $folderGenerator
    ) {
        $this->resourceRegistry = $resourceRegistry;
        $this->requestGenerator = $requestGenerator;
        $this->folderGenerator = $folderGenerator;
    }

    /**
     * @param string $baseUrl
     * @param string $name
     * @param bool   $public
     *
     * @return array
     */
    public function generate($baseUrl, $name = null, $public = false)
    {
        $configuration = [
            'id' => md5($name || time()),
            'name' => $name,
            'description' => '',
            'order' => [],
            'folders' => [],
            'timestamp' => 0,
            'owner' => 0,
            'remoteLink' => '',
            'public' => $public,
            'requests' => [],
        ];

        foreach ($this->resourceRegistry->getResources() as $resource) {
            $configuration['requests'] = array_merge(
                $configuration['requests'],
                $this->requestGenerator->generate($resource, $baseUrl)
            );
            $configuration['folders'][] = $this->folderGenerator->generate($resource, $configuration['id']);
        }

        foreach ($configuration['requests'] as $id => $request) {
            $configuration['requests'][$id]['collectionId'] = $configuration['id'];
        }

        return $configuration;
    }
}
