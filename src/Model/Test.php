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

class Test
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $executor;

    /**
     * @param string $message
     * @param string $executor
     */
    public function __construct($message = null, $executor = null)
    {
        $this->message = $message;
        $this->executor = $executor;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * @param string $executor
     */
    public function setExecutor($executor)
    {
        $this->executor = $executor;
    }
}
