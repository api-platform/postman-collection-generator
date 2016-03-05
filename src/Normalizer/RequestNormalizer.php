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
use PostmanGeneratorBundle\Model\Request;
use PostmanGeneratorBundle\Model\Test;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RequestNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Request $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = [];
        $reflectionClass = new \ReflectionClass($object);
        /** @var \ReflectionProperty[] $properties */
        $properties = array_filter($reflectionClass->getProperties(), function (\ReflectionProperty $property) {
            return 'resource' !== $property->getName();
        });

        foreach ($properties as $property) {
            if ('fromCollection' !== $property->getName()) {
                $method = $reflectionClass->getMethod('get'.Inflector::classify($property->getName()));
            } else {
                $method = $reflectionClass->getMethod('is'.Inflector::classify($property->getName()));
            }
            if ('folder' === $property->getName()) {
                $data[$property->getName()] = $method->invoke($object)->getId();
            } elseif ('collection' === $property->getName()) {
                $data[$property->getName().'Id'] = $method->invoke($object)->getId();
            } elseif ('headers' === $property->getName()) {
                $data[$property->getName()] = '';
                foreach ($method->invoke($object) as $key => $value) {
                    $data[$property->getName()] .= "$key: $value\n";
                }
            } elseif ('rawModeData' === $property->getName()) {
                $rawModeData = json_encode((object)$method->invoke($object));
                if ('{}' !== $rawModeData) {
                    $rawModeData = preg_replace('/([{,])/', "\$1\n    ", $rawModeData);
                    $rawModeData = preg_replace('/([}])/', "\n}", $rawModeData);
                }
                $data[$property->getName()] = $rawModeData;
            } elseif ('tests' === $property->getName()) {
                $tests = implode("\n\n", array_map(function (Test $test) {
                    return sprintf('tests["%s"] = %s;', $test->getMessage(), $test->getExecutor());
                }, $object->getTests()));
                $data[$property->getName()] = $tests;
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
        return 'json' === $format && is_object($data) && $data instanceof Request;
    }
}
