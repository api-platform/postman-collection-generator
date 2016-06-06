<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\Faker\Guesser;

use Doctrine\Common\Inflector\Inflector;
use Dunglas\ApiBundle\Api\IriConverterInterface;
use Dunglas\ApiBundle\Api\ResourceCollectionInterface;
use Dunglas\ApiBundle\Mapping\AttributeMetadataInterface;
use Dunglas\ApiBundle\Mapping\ClassMetadataFactoryInterface;
use Faker\Generator;
use Faker\Guesser\Name;

class Guesser extends Name
{
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    /**
     * @var ResourceCollectionInterface
     */
    private $resourceCollection;

    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @param Generator                     $generator
     * @param IriConverterInterface         $iriConverter
     * @param ResourceCollectionInterface   $resourceCollection
     * @param ClassMetadataFactoryInterface $classMetadataFactory
     */
    public function __construct(
        Generator $generator,
        IriConverterInterface $iriConverter,
        ResourceCollectionInterface $resourceCollection,
        ClassMetadataFactoryInterface $classMetadataFactory
    ) {
        parent::__construct($generator);

        $this->iriConverter = $iriConverter;
        $this->resourceCollection = $resourceCollection;
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * @param AttributeMetadataInterface $attributeMetadata
     *
     * @return mixed
     */
    public function guess(AttributeMetadataInterface $attributeMetadata)
    {
        $value = null;
        $type = null;
        if (true === ($isDoctrine = isset($attributeMetadata->getTypes()[0]))) {
            $type = $attributeMetadata->getTypes()[0];
        }

        // Guess associations
        if ($isDoctrine && 'object' === $type->getType() && 'DateTime' !== $type->getClass()) {
            $class = $type->isCollection() ? $type->getCollectionType()->getClass() : $type->getClass();
            $resource = $this->resourceCollection->getResourceForEntity($class);
            $classMetadata = $this->classMetadataFactory->getMetadataFor(
                $resource->getEntityClass(),
                $resource->getNormalizationGroups(),
                $resource->getDenormalizationGroups(),
                $resource->getValidationGroups()
            );
            $id = $this->guess($classMetadata->getIdentifier());
            $value = $this->iriConverter->getIriFromResource($resource).'/'.$id;

            if ($type->isCollection()) {
                $value = [$value];
            }
        }

        // Guess by faker
        if (null === $value) {
            try {
                $value = call_user_func([$this->generator, $attributeMetadata->getName()]);
            } catch (\InvalidArgumentException $e) {
            }
        }

        // Guess by field name
        if (null === $value) {
            $value = $this->guessFormat(Inflector::tableize($attributeMetadata->getName()));
        }

        // Guess by Doctrine type
        if (null === $value && $isDoctrine) {
            switch ($type->getType()) {
                case 'string':
                    $value = $this->generator->sentence;
                    break;
                case 'int':
                    $value = $this->generator->numberBetween;
                    break;
                case 'bool':
                    $value = $this->generator->boolean;
                    break;
                case 'object':
                    if ('DateTime' !== $type->getClass()) {
                        throw new \InvalidArgumentException(sprintf(
                            'Unknown Doctrine object type %s in field %s',
                            $type->getClass(),
                            $attributeMetadata->getName()
                        ));
                    }

                    $value = $this->generator->dateTime;
                    break;
            }
        }

        return $this->clean($value);
    }

    /**
     * @param string   $name
     * @param int|null $size Length of field, if known
     *
     * @return mixed
     */
    public function guessFormat($name, $size = null)
    {
        if (null !== ($value = parent::guessFormat($name))) {
            return $value;
        }

        switch ($name) {
            case 'mobile':
            case 'mobile_phone':
            case 'mobilePhone':
                return $this->generator->mobileNumber;
            case 'fax':
                return $this->generator->phoneNumber;
        }

        if (preg_match('/_name$/', $name) || preg_match('/Name$/', $name)) {
            return $this->generator->name;
        }

        if (preg_match('/_time$/', $name) || preg_match('/Time$/', $name)) {
            return $this->generator->time;
        }

        return;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function clean($value)
    {
        if (is_array($value)) {
            $value = array_map([$this, 'clean'], $value);
        }

        if ($value instanceof \Closure) {
            $value = call_user_func($value);
        }

        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        }

        return preg_replace("/\n/", ', ', $value);
    }
}
