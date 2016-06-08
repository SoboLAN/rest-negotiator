<?php

namespace SoboLAN\RestNegotiator\Tests\Serializer;

use SoboLAN\RestNegotiator\Serializer\RestSerializer;

class RestSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildDefaultSerializer()
    {
        $transformer = $this->getMockBuilder('SoboLAN\RestNegotiator\Transformers\ListTransformer')
            ->disableOriginalConstructor()
            ->getMock();
        $encoder = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\JsonEncoder')
            ->disableOriginalConstructor()
            ->getMock();
        $newTransformer = $this->getMockBuilder('SoboLAN\RestNegotiator\Transformers\ListTransformer')
            ->disableOriginalConstructor()
            ->getMock();

        $serializer = new RestSerializer(array($transformer), array($encoder));

        $serializer->replaceTransformers(array($newTransformer));

        $this->assertContains($newTransformer, $serializer->getTransformers());
    }

    public function testMeeticSerializerBadTransformers()
    {
        $badTransformer = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\JsonEncoder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->setExpectedException('Symfony\Component\Serializer\Exception\UnexpectedValueException');

        new RestSerializer(array($badTransformer));
    }
}
