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

class Collection
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var array
     */
    private $order = [];

    /**
     * @var Folder[]
     */
    private $folders = [];

    /**
     * @var int
     */
    private $timestamp = 0;

    /**
     * @var int
     */
    private $owner = 0;

    /**
     * @var string
     */
    private $remoteLink = '';

    /**
     * @var bool
     */
    private $public = false;

    /**
     * @var Request[]
     */
    private $requests = [];

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
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param array $order
     */
    public function setOrder(array $order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getRemoteLink()
    {
        return $this->remoteLink;
    }

    /**
     * @param string $remoteLink
     */
    public function setRemoteLink($remoteLink)
    {
        $this->remoteLink = $remoteLink;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param bool $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return Folder[]
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param Folder $folder
     */
    public function addFolder(Folder $folder)
    {
        $folder->setCollection($this);
        $this->folders[] = $folder;
        $this->mergeRequests($folder->getRequests());
    }

    /**
     * @return Request[]
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param Request $request
     */
    public function addRequest(Request $request)
    {
        $request->setCollection($this);
        $this->requests[] = $request;
    }

    /**
     * @param Request[] $requests
     */
    public function mergeRequests(array $requests)
    {
        foreach ($requests as $request) {
            $this->addRequest($request);
        }
    }
}
