<?php

namespace Faker\Guesser;

use PostmanGeneratorBundle\Faker\Guesser\Guesser;

class GuesserTest extends \PHPUnit_Framework_TestCase
{
    public function testGuess($expected)
    {
        $generatorMock = $this->prophesize('Faker\Generator');
        $iriConverterMock = $this->prophesize('Dunglas\ApiBundle\Api\IriConverterInterface');
        $resourceCollectionMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceCollectionInterface');
        $classMetadataFactoryMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataFactoryInterface');
        $attributeMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\AttributeMetadataInterface');

        $guesser = new Guesser(
            $generatorMock->reveal(),
            $iriConverterMock->reveal(),
            $resourceCollectionMock->reveal(),
            $classMetadataFactoryMock->reveal()
        );

        $this->assertEquals($expected, $guesser->guess($attributeMetadataMock->reveal()));
    }

    /**
     * @return array
     */
    public function getFakeData()
    {
        return [
            [
                // single association
                // collection
                // faker (`name`)
                // boolean
                // date
            ]
        ];
    }
}
