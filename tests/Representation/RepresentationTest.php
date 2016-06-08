<?php

namespace SoboLAN\RestNegotiator\Tests\Representation;

use SoboLAN\RestNegotiator\Representation\Representation;

class RepresentationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetter()
    {
        $nrItems = 10;
        
        $items = array();
        for ($i = 1; $i <= $nrItems; $i++) {
            $items[] = new \stdClass();
        }
        
        $representation = new Representation($items);
        
        $result = $representation->getItems();
        
        $this->assertEquals($result, $items);
    }
}
