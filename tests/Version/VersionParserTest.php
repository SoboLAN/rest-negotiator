<?php

namespace SoboLAN\RestNegotiator\Tests\Version;

use SoboLAN\RestNegotiator\Version\VersionParser;
use Symfony\Component\HttpFoundation\Request;

class VersionParserTest extends \PHPUnit_Framework_TestCase
{
    private $transformerName1 = 'OriginalTransformerName';
    private $transformerName2 = 'SomeTransformerName';
    
    private $versionsDeclaration = array(
        '1' => array('transformers' => 'OriginalTransformerName'),
        '2' => array('transformers' => 'SomeTransformerName'),
        '3' => array('transformers' => 'AnotherTransformerName'),
        'default_version' => 1
    );
    
    public function headerWithValidVersionsProvider()
    {
        return array(
            array('application/vendor+json;version=2', $this->transformerName2),
            array('application/vendor.v3+json', $this->transformerName1)
        );
    }
    
    /**
     * @dataProvider headerWithValidVersionsProvider
     */
    public function testGetTransformersNamesByVersion($header, $transf)
    {
        $versionParser = new VersionParser();
        
        $request = new Request();
        $request->headers->set('Accept', $header);
        $request->attributes->set('versions', $this->versionsDeclaration);
        
        $result = $versionParser->getTransformersNamesByVersion($request, 'Accept');
        
        $this->assertEquals($transf, $result);
    }
    
    public function headerWithInvalidVersionsProvider()
    {
        return array(
            array('application/vendor.v12345+json', $this->transformerName1),
        );
    }
    
    /**
     * @dataProvider headerWithInvalidVersionsProvider
     */
    public function testGetTransformersNamesWithInvalidVersions($header, $transf)
    {
        $versionParser = new VersionParser();
        
        $request = new Request();
        $request->headers->set('Accept', $header);
        $request->attributes->set('versions', $this->versionsDeclaration);
        
        $result = $versionParser->getTransformersNamesByVersion($request, 'Accept');
        
        $this->assertEquals($transf, $result);
    }
    
    public function headerWithNoVersionsProvider()
    {
        return array(
            array('application/vendor+json', $this->transformerName1),
        );
    }
    
    /**
     * @dataProvider headerWithNoVersionsProvider
     */
    public function testGetTransformersNamesWithNoVersions($header, $transf)
    {
        $versionParser = new VersionParser();
        
        $request = new Request();
        $request->headers->set('Accept', $header);
        $request->attributes->set('versions', $this->versionsDeclaration);
        
        $result = $versionParser->getTransformersNamesByVersion($request, 'Accept');
        
        $this->assertEquals($transf, $result);
    }
}
