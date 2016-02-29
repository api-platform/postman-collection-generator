<?php

namespace Generator;

use PostmanGeneratorBundle\Generator\RequestGenerator;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class RequestGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $authenticationGeneratorMock;

    /**
     * @var ObjectProphecy
     */
    private $classMetadataFactoryMock;

    /**
     * @var ObjectProphecy
     */
    private $resourceMock;

    /**
     * @var ObjectProphecy
     */
    private $attributesLoaderMock;

    /**
     * @var ObjectProphecy
     */
    private $guesserMock;

    /**
     * @var ObjectProphecy
     */
    private $requestParserFactoryMock;

    /**
     * @var ObjectProphecy
     */
    private $annotationReaderMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->authenticationGeneratorMock = $this->prophesize('PostmanGeneratorBundle\Generator\AuthenticationGenerator');
        $this->classMetadataFactoryMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataFactoryInterface');
        $this->resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');
        $this->attributesLoaderMock = $this->prophesize('Dunglas\ApiBundle\Mapping\Loader\AttributesLoader');
        $this->guesserMock = $this->prophesize('PostmanGeneratorBundle\Faker\Guesser\Guesser');
        $this->requestParserFactoryMock = $this->prophesize('PostmanGeneratorBundle\RequestParser\RequestParserFactory');
        $this->annotationReaderMock = $this->prophesize('Doctrine\Common\Annotations\AnnotationReader');
        $operationMock = $this->prophesize('Dunglas\ApiBundle\Api\Operation\OperationInterface');
        $routeMock = $this->prophesize('Symfony\Component\Routing\Route');
        $classMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\ClassMetadataInterface');
        $attributeMetadataMock = $this->prophesize('Dunglas\ApiBundle\Mapping\AttributeMetadata');
        $reflectionClassMock = $this->prophesize('\ReflectionClass');
        $reflectionPropertyMock = $this->prophesize('\ReflectionProperty');
        $groupsMock = $this->prophesize('Symfony\Component\Serializer\Annotation\Groups');

        $this->resourceMock->getCollectionOperations()->willReturn([
            $operationMock->reveal(),
            $operationMock->reveal(),
        ])->shouldBeCalledTimes(1);
        $this->resourceMock->getItemOperations()->willReturn([
            $operationMock->reveal(),
            $operationMock->reveal(),
            $operationMock->reveal(),
            $operationMock->reveal(),
        ])->shouldBeCalledTimes(1);

        $operationMock->getRoute()->willReturn($routeMock->reveal())->shouldBeCalledTimes(6);
        $routeMock->getMethods()
            ->willReturn(['GET'], ['POST'], ['GET'], ['PUT'], ['DELETE'], ['GET'])
            ->shouldBeCalledTimes(6);
        $routeMock->getDefault('_controller')->willReturn(
            'DunglasApiBundle:Resource:cget',
            'DunglasApiBundle:Resource:cpost',
            'DunglasApiBundle:Resource:get',
            'DunglasApiBundle:Resource:put',
            'DunglasApiBundle:Resource:delete',
            'AppBundle:Resource:foo'
        )->shouldBeCalledTimes(6);

        $this->resourceMock->getShortName()->willReturn('User')->shouldBeCalledTimes(6);
        $operationMock->getContext()
            ->willReturn([], [], [], [], [], ['hydra:title' => 'Get current user profile'])
            ->shouldBeCalledTimes(7);

        $routeMock->getPath()
            ->willReturn('/users', '/users', '/users/{id}', '/users/{id}', '/users/{id}', '/profile')
            ->shouldBeCalledTimes(6);

        $this->resourceMock->getEntityClass()->willReturn('\User')->shouldBeCalledTimes(1);
        $this->resourceMock->getNormalizationGroups()->shouldBeCalledTimes(1);
        $this->resourceMock->getDenormalizationGroups()->willReturn(['default_input'])->shouldBeCalledTimes(5);
        $this->resourceMock->getValidationGroups()->shouldBeCalledTimes(1);
        $this->classMetadataFactoryMock->getMetadataFor('\User', null, ['default_input'], null)
            ->willReturn($classMetadataMock->reveal())
            ->shouldBeCalledTimes(1);

        $classMetadataMock->getAttributes()->willReturn([
            $attributeMetadataMock->reveal(),
            $attributeMetadataMock->reveal(),
            $attributeMetadataMock->reveal(),
            $attributeMetadataMock->reveal(),
        ])->shouldBeCalledTimes(2);
        $classMetadataMock->getReflectionClass()->willReturn($reflectionClassMock->reveal())->shouldBeCalledTimes(8);
        $reflectionClassMock->getProperty(Argument::any())->willReturn($reflectionPropertyMock->reveal())->shouldBeCalledTimes(8);
        $this->annotationReaderMock->getPropertyAnnotation($reflectionPropertyMock->reveal(), 'Symfony\Component\Serializer\Annotation\Groups')
            ->willReturn(null, null, $groupsMock->reveal(), $groupsMock->reveal())
            ->shouldBeCalledTimes(8);
        $groupsMock->getGroups()->willReturn([], ['default_input'])->shouldBeCalledTimes(4);
        $attributeMetadataMock->getName()->willReturn('name', 'description')->shouldBeCalledTimes(11);
        $attributeMetadataMock->isIdentifier()->willReturn(true, false, false, false, true, false, false, false)->shouldBeCalledTimes(8);
        $attributeMetadataMock->isReadable()->willReturn(false, true, true, false, true, true)->shouldBeCalledTimes(6);
        $this->guesserMock->guess($attributeMetadataMock->reveal())->willReturn('')->shouldBeCalledTimes(3);
        $this->requestParserFactoryMock->parse(Argument::any())->shouldBeCalledTimes(6);
    }

    public function testGenerate()
    {
        $authenticatorMock = $this->prophesize('PostmanGeneratorBundle\Authenticator\AuthenticatorInterface');
        $this->authenticationGeneratorMock->get('oauth2')
            ->willReturn($authenticatorMock->reveal())
            ->shouldBeCalledTimes(6);
        $authenticatorMock->generate(Argument::type('PostmanGeneratorBundle\Model\Request'))->shouldBeCalledTimes(6);

        $generator = new RequestGenerator(
            $this->classMetadataFactoryMock->reveal(),
            $this->attributesLoaderMock->reveal(),
            $this->authenticationGeneratorMock->reveal(),
            $this->guesserMock->reveal(),
            $this->requestParserFactoryMock->reveal(),
            $this->annotationReaderMock->reveal(),
            'oauth2'
        );

        $requests = $generator->generate($this->resourceMock->reveal());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[0]->getId());
        $this->assertEquals('/users', $requests[0]->getUrl());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Get users list', $requests[0]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[1]->getId());
        $this->assertEquals('/users', $requests[1]->getUrl());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertEquals('Create user', $requests[1]->getName());
        $this->assertEquals(['Content-Type' => 'application/json'], $requests[1]->getHeaders());
        $this->assertEquals('raw', $requests[1]->getDataMode());
        $this->assertEquals(['description' => ''], $requests[1]->getRawModeData());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[2]->getId());
        $this->assertEquals('/users/{id}', $requests[2]->getUrl());
        $this->assertEquals('GET', $requests[2]->getMethod());
        $this->assertEquals('Get user', $requests[2]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[3]->getId());
        $this->assertEquals('/users/{id}', $requests[3]->getUrl());
        $this->assertEquals('PUT', $requests[3]->getMethod());
        $this->assertEquals('Update user', $requests[3]->getName());
        $this->assertEquals(['Content-Type' => 'application/json'], $requests[3]->getHeaders());
        $this->assertEquals('raw', $requests[3]->getDataMode());
        $this->assertEquals(['description' => ''], $requests[3]->getRawModeData());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[4]->getId());
        $this->assertEquals('/users/{id}', $requests[4]->getUrl());
        $this->assertEquals('DELETE', $requests[4]->getMethod());
        $this->assertEquals('Delete user', $requests[4]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[5]->getId());
        $this->assertEquals('/profile', $requests[5]->getUrl());
        $this->assertEquals('GET', $requests[5]->getMethod());
        $this->assertEquals('Get current user profile', $requests[5]->getName());
    }

    public function testGenerateWithoutAuthentication()
    {
        $this->authenticationGeneratorMock->get('oauth2')->shouldNotBeCalled();

        $generator = new RequestGenerator(
            $this->classMetadataFactoryMock->reveal(),
            $this->attributesLoaderMock->reveal(),
            $this->authenticationGeneratorMock->reveal(),
            $this->guesserMock->reveal(),
            $this->requestParserFactoryMock->reveal(),
            $this->annotationReaderMock->reveal()
        );

        $requests = $generator->generate($this->resourceMock->reveal());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[0]->getId());
        $this->assertEquals('/users', $requests[0]->getUrl());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Get users list', $requests[0]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[1]->getId());
        $this->assertEquals('/users', $requests[1]->getUrl());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertEquals('Create user', $requests[1]->getName());
        $this->assertEquals(['Content-Type' => 'application/json'], $requests[1]->getHeaders());
        $this->assertEquals('raw', $requests[1]->getDataMode());
        $this->assertEquals(['description' => ''], $requests[1]->getRawModeData());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[2]->getId());
        $this->assertEquals('/users/{id}', $requests[2]->getUrl());
        $this->assertEquals('GET', $requests[2]->getMethod());
        $this->assertEquals('Get user', $requests[2]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[3]->getId());
        $this->assertEquals('/users/{id}', $requests[3]->getUrl());
        $this->assertEquals('PUT', $requests[3]->getMethod());
        $this->assertEquals('Update user', $requests[3]->getName());
        $this->assertEquals(['Content-Type' => 'application/json'], $requests[3]->getHeaders());
        $this->assertEquals('raw', $requests[3]->getDataMode());
        $this->assertEquals(['description' => ''], $requests[3]->getRawModeData());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[4]->getId());
        $this->assertEquals('/users/{id}', $requests[4]->getUrl());
        $this->assertEquals('DELETE', $requests[4]->getMethod());
        $this->assertEquals('Delete user', $requests[4]->getName());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $requests[5]->getId());
        $this->assertEquals('/profile', $requests[5]->getUrl());
        $this->assertEquals('GET', $requests[5]->getMethod());
        $this->assertEquals('Get current user profile', $requests[5]->getName());
    }
}
