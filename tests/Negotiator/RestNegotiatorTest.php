<?php

namespace SoboLAN\RestNegotiator\Tests\Negotiator;

use SoboLAN\RestNegotiator\Representation\Representation;
use SoboLAN\RestNegotiator\Negotiator\RestNegotiator;
use SoboLAN\RestNegotiator\Format\FormatParser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestNegotiatorTest extends \PHPUnit_Framework_TestCase
{
    private $serializerFactoryMock;
    private $versionParserMock;
    private $formatParserMock;
    private $defaultTransformersNames = array('sobolan.restnegotiator.transformer.list');
    private $defaultEncodersNames = array('sobolan.restnegotiator.encoder.json');
    private $overwrittenTransformersNames = array('sobolan.restnegotiator.transformer.overw.list');

    public function setUp()
    {
        $this->serializerFactoryMock = $this->getMockBuilder(
            'SoboLAN\RestNegotiator\Factory\RestSerializerFactory'
        )
            ->disableOriginalConstructor()
            ->setMethods(array('buildDefaultSerializer', 'overwriteTransformers'))
            ->getMock();

        $this->versionParserMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Version\VersionParser')
            ->disableOriginalConstructor()
            ->getMock();

        $this->formatParserMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Format\FormatParser')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetResponse()
    {
        $items = array();
        for ($i = 1; $i <= 10; $i++) {
            $items[] = array('some key' => $i);
        }

        $representation = new Representation($items);
        $statusCode = Response::HTTP_OK;
        $headers = array('Accept' => 'application/vendor;format=json;version=1');
        $context = array();
        $serializedData = json_encode($items);

        $request = new Request();
        $request->headers->set('Accept', $headers['Accept']);

        $requestStackMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
            ->disableOriginalConstructor()
            ->getMock();

        $requestStackMock->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $meeticSerializerMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Tests\Negotiator\MockInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $meeticSerializerMock->expects($this->once())
            ->method('serialize')
            ->with(
                $this->equalTo($representation),
                $this->equalTo(FormatParser::DEFAULT_FORMAT),
                $this->equalTo($context)
            )
            ->will($this->returnValue($serializedData));

        $this->serializerFactoryMock->expects($this->once())
            ->method('buildDefaultSerializer')
            ->with(
                $this->equalTo($this->defaultTransformersNames),
                $this->equalTo($this->defaultEncodersNames)
            )
            ->will($this->returnValue($meeticSerializerMock));

        $this->versionParserMock->expects($this->atLeastOnce())
            ->method('getTransformersNamesByVersion')
            ->with($this->equalTo($request), $this->equalTo('Accept'))
            ->will($this->returnValue($this->overwrittenTransformersNames));

        $this->serializerFactoryMock->expects($this->exactly(2))
            ->method('overwriteTransformers')
            ->with(
                $this->equalTo($meeticSerializerMock),
                $this->logicalOr(
                    $this->equalTo($this->defaultTransformersNames),
                    $this->equalTo($this->overwrittenTransformersNames)
                )
            );

        $this->formatParserMock->expects($this->atLeastOnce())
            ->method('getSupportedFormat')
            ->with(
                $this->equalTo($request),
                $this->equalTo('Accept'),
                $this->equalTo($meeticSerializerMock)
            )
            ->will($this->returnValue(FormatParser::DEFAULT_FORMAT));

        $restNegotiator = new RestNegotiator(
            $requestStackMock,
            $this->serializerFactoryMock,
            $this->versionParserMock,
            $this->formatParserMock,
            $this->defaultTransformersNames,
            $this->defaultEncodersNames
        );

        $result = $restNegotiator->getResponse($representation, $statusCode, $headers, $context);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $result);
        $this->assertEquals($serializedData, $result->getContent());
    }

    public function testGetDeserialized()
    {
        $objects = array();
        $items = array();
        for ($i = 1; $i <= 10; $i++) {
            $objects[] = new \stdClass();
            $items[] = array('some key' => $i);
        }

        $className = 'some\class\name';
        $serializedData = json_encode($items);

        $meeticSerializerMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Tests\Negotiator\MockInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $meeticSerializerMock->expects($this->once())
            ->method('deserialize')
            ->with(
                $this->equalTo($serializedData),
                $this->equalTo($className),
                $this->equalTo(FormatParser::DEFAULT_FORMAT)
            )
            ->will($this->returnValue($objects));

        $this->serializerFactoryMock->expects($this->once())
            ->method('buildDefaultSerializer')
            ->with(
                $this->equalTo($this->defaultTransformersNames),
                $this->equalTo($this->defaultEncodersNames)
            )
            ->will($this->returnValue($meeticSerializerMock));

        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($serializedData));

        $this->versionParserMock->expects($this->atLeastOnce())
            ->method('getTransformersNamesByVersion')
            ->with(
                $this->equalTo($requestMock),
                $this->equalTo('Content-Type')
            )
            ->will($this->returnValue($this->overwrittenTransformersNames));

        $this->serializerFactoryMock->expects($this->exactly(2))
            ->method('overwriteTransformers')
            ->with(
                $this->equalTo($meeticSerializerMock),
                $this->logicalOr(
                    $this->equalTo($this->defaultTransformersNames),
                    $this->equalTo($this->overwrittenTransformersNames)
                )
            );

        $this->formatParserMock->expects($this->atLeastOnce())
            ->method('getSupportedFormat')
            ->with(
                $this->equalTo($requestMock),
                $this->equalTo('Content-Type'),
                $this->equalTo($meeticSerializerMock)
            )
            ->will($this->returnValue(FormatParser::DEFAULT_FORMAT));

        $requestStackMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
            ->disableOriginalConstructor()
            ->getMock();

        $requestStackMock->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn($requestMock);

        $meeticSerializerMock = $this->getMockBuilder('SoboLAN\RestNegotiator\Tests\Negotiator\MockInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $restNegotiator = new RestNegotiator(
            $requestStackMock,
            $this->serializerFactoryMock,
            $this->versionParserMock,
            $this->formatParserMock,
            $this->defaultTransformersNames,
            $this->defaultEncodersNames
        );

        $deserializedData = $restNegotiator->getDeserialized($className);

        $this->assertEquals($objects, $deserializedData);
    }
}
