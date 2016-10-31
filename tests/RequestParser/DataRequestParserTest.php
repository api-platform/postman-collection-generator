<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
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

    /**
     * @dataProvider getDataProvider
     *
     * @param string $name
     * @param array  $options
     */
    public function testParse($name, array $options)
    {
        $classMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataInterface');
        $resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');
        $reflectionClassMock = $this->prophesize('\ReflectionClass');
        $reflectionPropertyMock = $this->prophesize('\ReflectionProperty');
        $attributeMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\AttributeMetadata');
        $groupsMock = $this->prophesize('Symfony\Component\Serializer\Annotation\Groups');

        $resourceMock->getEntityClass()->willReturn('\User')->shouldBeCalledTimes(1);
        $resourceMock->getNormalizationGroups()->willReturn(['user_output'])->shouldBeCalledTimes(1);
        $resourceMock->getDenormalizationGroups()->willReturn(['user_input'])->shouldBeCalled();
        $resourceMock->getValidationGroups()->shouldBeCalledTimes(1);

        $this->requestMock->getResource()->willReturn($resourceMock->reveal())->shouldBeCalledTimes(1);
        $this->classMetadataFactoryMock->getMetadataFor('\User', ['user_output'], ['user_input'], null)
            ->willReturn($classMetadataMock->reveal())
            ->shouldBeCalledTimes(1);

        $this->requestMock->addHeader('Content-Type', 'application/json')->shouldBeCalledTimes(1);
        $this->requestMock->setDataMode(Request::DATA_MODE_RAW)->shouldBeCalledTimes(1);

        $classMetadataMock->getAttributes()->willReturn([$attributeMetadataMock->reveal()])->shouldBeCalledTimes(1);

        $attributeMetadataMock->getName()->willReturn($name)->shouldBeCalled();
        $classMetadataMock->getReflectionClass()->willReturn($reflectionClassMock->reveal())->shouldBeCalled();

        $reflectionClassMock->hasProperty($name)->willReturn($options['property'])->shouldBeCalledTimes(1);
        if ($options['property']) {
            $reflectionClassMock->getProperty($name)
                ->willReturn($reflectionPropertyMock->reveal())
                ->shouldBeCalledTimes(1);
            $this->readerMock->getPropertyAnnotation(
                $reflectionPropertyMock->reveal(),
                'Symfony\Component\Serializer\Annotation\Groups'
            )->willReturn($groupsMock->reveal())->shouldBeCalledTimes(1);

            $attributeMetadataMock->isIdentifier()->willReturn($options['identifier'])->shouldBeCalledTimes(1);
            if (!$options['identifier']) {
                $attributeMetadataMock->isReadable()->willReturn($options['readable'])->shouldBeCalledTimes(1);
                if ($options['readable']) {
                    $groupsMock->getGroups()->willReturn($options['groups'])->shouldBeCalledTimes(1);

                    if (in_array('user_input', $options['groups'])) {
                        $this->guesserMock->guess($attributeMetadataMock->reveal())
                            ->willReturn('bar')
                            ->shouldBeCalledTimes(1);
                    }
                }
            }
        }

        $this->requestMock->setRawModeData($options['value'])->shouldBeCalledTimes(1);

        $this->parser->parse($this->requestMock->reveal());
    }

    public function getDataProvider()
    {
        return [
            [
                'id', [
                    'property' => true,
                    'identifier' => true,
                    'readable' => true,
                    'groups' => ['user_input'],
                    'value' => [],
                ],
            ],
            [
                'name', [
                    'property' => true,
                    'identifier' => false,
                    'readable' => false,
                    'groups' => ['user_input'],
                    'value' => [],
                ],
            ],
            [
                'description', [
                    'property' => true,
                    'identifier' => false,
                    'readable' => true,
                    'groups' => [],
                    'value' => [],
                ],
            ],
            [
                'foo', [
                    'property' => true,
                    'identifier' => false,
                    'readable' => true,
                    'groups' => ['company_input'],
                    'value' => [],
                ],
            ],
            [
                'bar', [
                    'property' => true,
                    'identifier' => false,
                    'readable' => true,
                    'groups' => ['user_input', 'company_input'],
                    'value' => ['bar' => 'bar'],
                ],
            ],
            [
                'hasNotifications', [
                    'property' => false,
                    'identifier' => false,
                    'readable' => true,
                    'groups' => ['user_input', 'company_input'],
                    'value' => [],
                ],
            ],
        ];
    }
}
