<?php

namespace SoboLAN\RestNegotiator\Tests\Representation;

use SoboLAN\RestNegotiator\Representation\ListRepresentation;

class ListRepresentationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetter()
    {
        $nrItems = 10;
        
        $items = array();
        for ($i = 1; $i <= $nrItems; $i++) {
            $items[] = new \stdClass();
        }
        
        $representation = new ListRepresentation();
        
        $representation->setItems($items);
        
        $result = $representation->getItems();
        
        $this->assertEquals($result, $items);
    }
    
    public function testPushItem()
    {
        $nrItems = 10;
        
        $representation = new ListRepresentation();
        
        $items = array();
        for ($i = 1; $i <= $nrItems; $i++) {
            $item = new \stdClass();
            $items[] = $item;
            $representation->pushItem($item);
        }
        
        $result = $representation->getItems();
        
        $this->assertEquals($result, $items);
    }
    
    public function testPopItem()
    {
        $nrItems = 10;
        
        $items = array();
        for ($i = 1; $i <= $nrItems; $i++) {
            $items[] = new \stdClass();
        }
        
        $representation = new ListRepresentation();
        
        $representation->setItems($items);
        
        $item = $representation->popItem();
        $result = array_pop($items);
        
        $this->assertEquals($result, $item);
    }
    
    public function testPagination()
    {
        $pagination = array('pagination' => array('some', 'stuff', 'here'));
        
        $representation = new ListRepresentation();
        
        $representation->setPagination($pagination);
        
        $result = $representation->getPagination();
        
        $this->assertEquals($result, $pagination);
    }
}
