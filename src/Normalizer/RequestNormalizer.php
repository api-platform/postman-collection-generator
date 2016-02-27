<?php

namespace PostmanGeneratorBundle\Normalizer;

use Doctrine\Common\Inflector\Inflector;
use PostmanGeneratorBundle\Model\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RequestNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param Request $object
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = [];
        $reflectionClass = new \ReflectionClass($object);

        foreach ($reflectionClass->getProperties() as $property) {
            $method = $reflectionClass->getMethod('get'.Inflector::classify($property->getName()));
            if ('folder' === $property->getName()) {
                $data[$property->getName()] = $method->invoke($object)->getId();
            } elseif ('collection' === $property->getName()) {
                $data[$property->getName().'Id'] = $method->invoke($object)->getId();
            } elseif ('headers' === $property->getName()) {
                $data[$property->getName()] = '';
                foreach ($method->invoke($object) as $key => $value) {
                    $data[$property->getName()].= "$key: $value\n";
                }
            } elseif ('rawModeData' === $property->getName()) {
                $rawModeData = json_encode($method->invoke($object));
                $rawModeData = preg_replace('/([{,])/', "\$1\n    ", $rawModeData);
                $rawModeData = preg_replace('/([}])/', "\n}", $rawModeData);
                $data[$property->getName()] = $rawModeData;
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
