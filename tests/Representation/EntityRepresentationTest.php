<?php

namespace SoboLAN\RestNegotiator\Tests\Representation;

use SoboLAN\RestNegotiator\Representation\EntityRepresentation;

class EntityRepresentationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetter()
    {
        $entity = new \stdClass();
        
        $representation = new EntityRepresentation($entity);
        
        $result = $representation->getEntity();
        
        $this->assertEquals($result, $entity);
    }
}
