<?php

namespace SoboLAN\RestNegotiator\Representation;

use SoboLAN\RestNegotiator\Representation\RepresentationInterface;

class ListRepresentation implements RepresentationInterface
{
    /**
     * To be removed when minimum phpversion >= 5.5
     * @see http://php.net/oop5.basic#language.oop5.basic.class.class
     */
    const CLASS_NAME = __CLASS__;

    private $items = array();
    private $pagination;

    public function pushItem($item)
    {
        array_push($this->items, $item);
    }

    public function popItem()
    {
        return array_pop($this->items);
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setPagination(array $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination()
    {
        return $this->pagination;
    }
}
