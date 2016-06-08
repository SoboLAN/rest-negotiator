<?php

namespace SoboLAN\RestNegotiator\Tests\Factory;

use SoboLAN\RestNegotiator\Factory\RestSerializerFactory;
use Symfony\Component\DependencyInjection\Container;

class RestSerializerFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $listTransformerMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Transformers\ListTransformer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->container = new Container();
        $this->container->set('sobolan.restnegotiator.transformer.list', $listTransformerMock);
    }

    public function testSubclassOfContainerAware()
    {
        $serializerFactory = new RestSerializerFactory();

        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerAwareInterface', $serializerFactory);
    }

    public function testBuildDefaultSerializer()
    {
        $defaultTransformersNames = array('sobolan.restnegotiator.transformer.list');
        $defaultEncodersNames = array('sobolan.restnegotiator.encoder.json');

        $jsonEncoderMock = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\JsonEncoder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('sobolan.restnegotiator.encoder.json', $jsonEncoderMock);

        $serializerFactory = new RestSerializerFactory();
        $serializerFactory->setContainer($this->container);

        $instance = $serializerFactory->buildDefaultSerializer($defaultTransformersNames, $defaultEncodersNames);

        $this->assertInstanceOf('SoboLAN\RestNegotiator\Serializer\RestSerializerInterface', $instance);
    }

    public function testOverwriteTransformers()
    {
        $serializerMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Serializer\RestSerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $serializerMock->expects($this->once())
            ->method('replaceTransformers')
            ->with($this->isType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY));

        $transformersNames = array('sobolan.restnegotiator.transformer.list');

        $serializerFactory = new RestSerializerFactory();
        $serializerFactory->setContainer($this->container);

        $serializerFactory->overwriteTransformers($serializerMock, $transformersNames);
    }
}
