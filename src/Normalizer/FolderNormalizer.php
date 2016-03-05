<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use PostmanGeneratorBundle\Model\Folder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FolderNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Folder $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = [];
        $reflectionClass = new \ReflectionClass($object);
        /** @var \ReflectionProperty[] $properties */
        $properties = array_filter($reflectionClass->getProperties(), function (\ReflectionProperty $property) {
            return 'requests' !== $property->getName();
        });

        foreach ($properties as $property) {
            $method = $reflectionClass->getMethod('get'.Inflector::classify($property->getName()));
            if ('collection' === $property->getName()) {
                $data[$property->getName().'Id'] = $method->invoke($object)->getId();
            } else {
                $data[$property->getName()] = $method->invoke($object);
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return 'json' === $format && is_object($data) && $data instanceof Folder;
    }
}
