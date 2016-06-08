<?php

namespace SoboLAN\RestNegotiator\Representation;

class Representation implements RepresentationInterface
{
    /**
     * To be removed when minimum phpversion >= 5.5
     * @see http://php.net/oop5.basic#language.oop5.basic.class.class
     */
    const CLASS_NAME = __CLASS__;

    private $items;

    public function __construct(array $items = null)
    {
        $this->setItems($items);
    }

    public function setItems(array $items = null)
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }
}
