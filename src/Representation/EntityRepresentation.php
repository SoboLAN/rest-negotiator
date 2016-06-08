<?php

namespace SoboLAN\RestNegotiator\Representation;

use SoboLAN\RestNegotiator\Representation\RepresentationInterface;

class EntityRepresentation implements RepresentationInterface
{
    /**
     * To be removed when minimum phpversion >= 5.5
     * @see http://php.net/oop5.basic#language.oop5.basic.class.class
     */
    const CLASS_NAME = __CLASS__;

    private $entity;

    public function __construct($entity)
    {
        $this->setEntity($entity);
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
