<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\RequestParser;

use Doctrine\Common\Annotations\Reader;
use Dunglas\ApiBundle\Mapping\ClassMetadataFactoryInterface;
use PostmanGeneratorBundle\Faker\Guesser\Guesser;
use PostmanGeneratorBundle\Model\Request;

class DataRequestParser implements RequestParserInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Guesser
     */
    private $guesser;

    /**
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     * @param Reader                        $reader
     * @param Guesser                       $guesser
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory, Reader $reader, Guesser $guesser)
    {
        $this->classMetadataFactory = $classMetadataFactory;
        $this->reader = $reader;
        $this->guesser = $guesser;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        $resource = $request->getResource();
        $classMetadata = $this->classMetadataFactory->getMetadataFor(
            $resource->getEntityClass(),
            $resource->getNormalizationGroups(),
            $resource->getDenormalizationGroups(),
            $resource->getValidationGroups()
        );
        $request->addHeader('Content-Type', 'application/json');
        $request->setDataMode(Request::DATA_MODE_RAW);

        $rawModeData = [];
        foreach ($classMetadata->getAttributes() as $attributeMetadata) {
            if (!$classMetadata->getReflectionClass()->hasProperty($attributeMetadata->getName())) {
                // Attribute is not a property: ignore it
                continue;
            }
            $groups = $this->reader->getPropertyAnnotation(
                $classMetadata->getReflectionClass()->getProperty($attributeMetadata->getName()),
                'Symfony\Component\Serializer\Annotation\Groups'
            );
            if (
                $attributeMetadata->isIdentifier() ||
                !$attributeMetadata->isReadable() ||
                !count(array_intersect($groups ? $groups->getGroups() : [], $resource->getDenormalizationGroups() ?: []))
            ) {
                continue;
            }

            $rawModeData[$attributeMetadata->getName()] = $this->guesser->guess($attributeMetadata);
        }
        $request->setRawModeData($rawModeData);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']);
    }
}
