<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RequestParser;

use PostmanGeneratorBundle\Model\Request;
use PostmanGeneratorBundle\RequestParser\DataRequestParser;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class DataRequestParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataRequestParser
     */
    private $parser;

    /**
     * @var ObjectProphecy
     */
    private $classMetadataFactoryMock;

    /**
     * @var ObjectProphecy
     */
    private $readerMock;

    /**
     * @var ObjectProphecy
     */
    private $guesserMock;

    /**
     * @var ObjectProphecy
     */
    private $requestMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->classMetadataFactoryMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataFactoryInterface');
        $this->readerMock = $this->prophesize('Doctrine\Common\Annotations\Reader');
        $this->guesserMock = $this->prophesize('PostmanGeneratorBundle\Faker\Guesser\Guesser');
        $this->requestMock = $this->prophesize('PostmanGeneratorBundle\Model\Request');

        $this->parser = new DataRequestParser(
            $this->classMetadataFactoryMock->reveal(),
            $this->readerMock->reveal(),
            $this->guesserMock->reveal()
        );
    }

    public function testSupports()
    {
        $this->requestMock->getMethod()->willReturn('POST', 'PUT', 'PATCH');

        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
        $this->assertTrue($this->parser->supports($this->requestMock->reveal()));
    }

    public function testNoSupports()
    {
        $this->requestMock->getMethod()->willReturn('GET', 'DELETE');

        $this->assertFalse($this->parser->supports($this->requestMock->reveal()));
        $this->assertFalse($this->parser->supports($this->requestMock->reveal()));
    }

    public function testParse()
    {
        $classMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataInterface');
        $resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');
        $reflectionClassMock = $this->prophesize('\ReflectionClass');
        $reflectionPropertyMock = $this->prophesize('\ReflectionProperty');
        $attributeMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\AttributeMetadata');
        $groupsMock = $this->prophesize('Symfony\Component\Serializer\Annotation\Groups');

        $resourceMock->getEntityClass()->willReturn('\User')->shouldBeCalledTimes(1);
        $resourceMock->getNormalizationGroups()->willReturn(['user_output'])->shouldBeCalledTimes(1);
        $resourceMock->getDenormalizationGroups()->willReturn(['user_input'])->shouldBeCalledTimes(4);
        $resourceMock->getValidationGroups()->shouldBeCalledTimes(1);

        $this->requestMock->getResource()->willReturn($resourceMock->reveal())->shouldBeCalledTimes(1);
        $this->classMetadataFactoryMock->getMetadataFor('\User', ['user_output'], ['user_input'], null)
            ->willReturn($classMetadataMock->reveal())
            ->shouldBeCalledTimes(1);

        $this->requestMock->addHeader('Content-Type', 'application/json')->shouldBeCalledTimes(1);
        $this->requestMock->setDataMode(Request::DATA_MODE_RAW)->shouldBeCalledTimes(1);

        $classMetadataMock->getAttributes()->willReturn([
            $attributeMetadataMock->reveal(), // identifier
            $attributeMetadataMock->reveal(), // not readable
            $attributeMetadataMock->reveal(), // no groups
            $attributeMetadataMock->reveal(), // wrong groups
            $attributeMetadataMock->reveal(), // right groups
        ])->shouldBeCalledTimes(1);

        $attributeMetadataMock->getName()
            ->willReturn('id', 'name', 'description', 'bar', 'foo', 'foo')
            ->shouldBeCalledTimes(6);
        $classMetadataMock->getReflectionClass()->willReturn($reflectionClassMock->reveal())->shouldBeCalledTimes(5);
        $reflectionClassMock->getProperty(Argument::type('string'))
            ->willReturn($reflectionPropertyMock->reveal())
            ->shouldBeCalledTimes(5);
        $this->readerMock->getPropertyAnnotation(
            $reflectionPropertyMock->reveal(),
            'Symfony\Component\Serializer\Annotation\Groups'
        )->willReturn(null, null, null, $groupsMock->reveal(), $groupsMock->reveal())->shouldBeCalledTimes(5);

        $attributeMetadataMock->isIdentifier()->willReturn(true, false, false, false, false)->shouldBeCalledTimes(5);
        $attributeMetadataMock->isReadable()->willReturn(false, true, true, true)->shouldBeCalledTimes(4);
        $groupsMock->getGroups()
            ->willReturn(['company_input'], ['user_input', 'company_input'])
            ->shouldBeCalledTimes(2);

        $this->guesserMock->guess($attributeMetadataMock->reveal())->willReturn('bar')->shouldBeCalledTimes(1);

        $this->requestMock->setRawModeData(['foo' => 'bar'])->shouldBeCalledTimes(1);

        $this->parser->parse($this->requestMock->reveal());
    }
}
