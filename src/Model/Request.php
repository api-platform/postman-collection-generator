<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\Model;

use Dunglas\ApiBundle\Api\ResourceInterface;

class Request
{
    const DATA_MODE_PARAMS = 'params';
    const DATA_MODE_RAW = 'raw';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var Test[]
     */
    private $tests = [];

    /**
     * @var Folder
     */
    private $folder;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $preRequestScript = '';

    /**
     * @var object
     */
    private $pathVariables;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $dataMode = self::DATA_MODE_PARAMS;

    /**
     * @var array
     */
    private $rawModeData = [];

    /**
     * @var int
     */
    private $version = 2;

    /**
     * @var string
     */
    private $currentHelper = 'normal';

    /**
     * @var object
     */
    private $helperAttributes;

    /**
     * @var int
     */
    private $time;

    /**
     * @var bool
     */
    private $fromCollection = false;

    /**
     * @var Request
     */
    private $collectionRequest;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var ResourceInterface
     */
    private $resource;

    /**
     * @var array
     */
    private $headers = [];

    public function __construct()
    {
        $this->pathVariables = new \stdClass();
        $this->helperAttributes = new \stdClass();
        $this->time = time();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return Test[]
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @param Test $test
     */
    public function addTest(Test $test)
    {
        $this->tests[] = $test;
    }

    /**
     * @return Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param Folder $folder
     */
    public function setFolder(Folder $folder = null)
    {
        $this->folder = $folder;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPreRequestScript()
    {
        return $this->preRequestScript;
    }

    /**
     * @param string $preRequestScript
     */
    public function setPreRequestScript($preRequestScript)
    {
        $this->preRequestScript = $preRequestScript;
    }

    /**
     * @return object
     */
    public function getPathVariables()
    {
        return $this->pathVariables;
    }

    /**
     * @param object $pathVariables
     */
    public function setPathVariables($pathVariables)
    {
        $this->pathVariables = $pathVariables;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getDataMode()
    {
        return $this->dataMode;
    }

    /**
     * @param string $dataMode
     */
    public function setDataMode($dataMode)
    {
        $this->dataMode = $dataMode;
    }

    /**
     * @return array
     */
    public function getRawModeData()
    {
        return $this->rawModeData;
    }

    /**
     * @param array $rawModeData
     */
    public function setRawModeData(array $rawModeData)
    {
        $this->rawModeData = $rawModeData;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getCurrentHelper()
    {
        return $this->currentHelper;
    }

    /**
     * @param string $currentHelper
     */
    public function setCurrentHelper($currentHelper)
    {
        $this->currentHelper = $currentHelper;
    }

    /**
     * @return object
     */
    public function getHelperAttributes()
    {
        return $this->helperAttributes;
    }

    /**
     * @param object $helperAttributes
     */
    public function setHelperAttributes($helperAttributes)
    {
        $this->helperAttributes = $helperAttributes;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return bool
     */
    public function isFromCollection()
    {
        return $this->fromCollection;
    }

    /**
     * @param bool $fromCollection
     */
    public function setFromCollection($fromCollection)
    {
        $this->fromCollection = $fromCollection;
    }

    /**
     * @return Request
     */
    public function getCollectionRequest()
    {
        return $this->collectionRequest;
    }

    /**
     * @param Request $collectionRequest
     */
    public function setCollectionRequest(Request $collectionRequest)
    {
        $this->collectionRequest = $collectionRequest;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param Collection $collection
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param ResourceInterface $resource
     */
    public function setResource(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }
}
