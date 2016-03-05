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

class Authentication
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
     * @var AuthenticationValue[]
     */
    private $values = [];

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var bool
     */
    private $synced = false;

    /**
     * @var string
     */
    private $syncedFilename = '';

    public function __construct()
    {
        $this->timestamp = time();
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
     * @return AuthenticationValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param AuthenticationValue $value
     */
    public function addValue(AuthenticationValue $value)
    {
        $this->values[] = $value;
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
     * @return bool
     */
    public function isSynced()
    {
        return $this->synced;
    }

    /**
     * @param bool $synced
     */
    public function setSynced($synced)
    {
        $this->synced = $synced;
    }

    /**
     * @return string
     */
    public function getSyncedFilename()
    {
        return $this->syncedFilename;
    }

    /**
     * @param string $syncedFilename
     */
    public function setSyncedFilename($syncedFilename)
    {
        $this->syncedFilename = $syncedFilename;
    }
}
