<?php

namespace SoboLAN\RestNegotiator\Tests\Format;

use SoboLAN\RestNegotiator\Format\FormatParser;

class FormatParserTest extends \PHPUnit_Framework_TestCase
{
    private $testFormat = 'mytestformat';
    
    public function validHeadersProvider()
    {
        return array(
            array('application/vendor;format=' . $this->testFormat, $this->testFormat),
            array('application/vendor', FormatParser::DEFAULT_FORMAT)
        );
    }
    
    /**
     * @dataProvider validHeadersProvider
     */
    public function testSupportedFormat($headerValue, $expectedFormat)
    {
        $headerName = "Accept";
        
        $headerBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\HeaderBag')
            ->disableOriginalConstructor()
            ->getMock();
        
        $headerBagMock->expects($this->atLeastOnce())
            ->method('has')
            ->with($this->equalTo($headerName))
            ->willReturn(true);
        
        $headerBagMock->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->equalTo($headerName))
            ->willReturn($headerValue);
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();
        
        $requestMock->headers = $headerBagMock;
        
        $serializerMock = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\EncoderInterface')
            ->disableOriginalConstructor()
            ->getMock();
        
        $serializerMock->expects($this->atLeastOnce())
            ->method('supportsEncoding')
            ->with($this->equalTo($expectedFormat))
            ->willReturn(true);
        
        $formatParser = new FormatParser();
        
        $format = $formatParser->getSupportedFormat($requestMock, $headerName, $serializerMock);
        
        $this->assertEquals($expectedFormat, $format);
    }
    
    /**
     * @dataProvider validHeadersProvider
     */
    public function testUnsupportedFormat($headerValue, $expectedFormat)
    {
        $headerName = "Accept";
        
        $headerBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\HeaderBag')
            ->disableOriginalConstructor()
            ->getMock();
        
        $headerBagMock->expects($this->atLeastOnce())
            ->method('has')
            ->with($this->equalTo($headerName))
            ->willReturn(true);
        
        $headerBagMock->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->equalTo($headerName))
            ->will($this->returnValue($headerValue));
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();
        
        $requestMock->headers = $headerBagMock;
        
        $serializerMock = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\EncoderInterface')
            ->disableOriginalConstructor()
            ->getMock();
        
        $serializerMock->expects($this->atLeastOnce())
            ->method('supportsEncoding')
            ->with($this->equalTo($expectedFormat))
            ->willReturn(false);
        
        $formatParser = new FormatParser();
        
        $this->setExpectedException('SoboLAN\RestNegotiator\Format\UnsupportedFormatException');
        
        $formatParser->getSupportedFormat($requestMock, $headerName, $serializerMock);
    }
    
    public function testSupportedFormatWithNonExistentHeader()
    {
        $headerName = "Accept";
        
        $headerBagMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\HeaderBag')
            ->disableOriginalConstructor()
            ->getMock();
        
        $headerBagMock->expects($this->atLeastOnce())
            ->method('has')
            ->with($this->equalTo($headerName))
            ->willReturn(false);
        
        $headerBagMock->expects($this->never())
            ->method('get');
        
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();
        
        $requestMock->headers = $headerBagMock;
        
        $serializerMock = $this->getMockBuilder('Symfony\Component\Serializer\Encoder\EncoderInterface')
            ->disableOriginalConstructor()
            ->getMock();
        
        $serializerMock->expects($this->atLeastOnce())
            ->method('supportsEncoding')
            ->with($this->equalTo(FormatParser::DEFAULT_FORMAT))
            ->willReturn(true);
        
        $formatParser = new FormatParser();
        
        $format = $formatParser->getSupportedFormat($requestMock, $headerName, $serializerMock);
        
        $this->assertEquals(FormatParser::DEFAULT_FORMAT, $format);
    }
}
