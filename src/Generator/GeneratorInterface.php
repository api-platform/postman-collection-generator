<?php

namespace PostmanGeneratorBundle\Generator;

use Dunglas\ApiBundle\Api\ResourceInterface;

interface GeneratorInterface
{
    /**
     * @param ResourceInterface $resource
     *
     * @return mixed
     */
    public function generate(ResourceInterface $resource = null);
}
