<?php

namespace SoboLAN\RestNegotiator\Representation;

use SoboLAN\RestNegotiator\Representation\RepresentationInterface;

class ExceptionRepresentation implements RepresentationInterface
{
    /**
     * To be removed when minimum phpversion >= 5.5
     * @see http://php.net/oop5.basic#language.oop5.basic.class.class
     */
    const CLASS_NAME = __CLASS__;

    private $exception;

    public function __construct(\Exception $exception)
    {
        $this->setException($exception);
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }
}
