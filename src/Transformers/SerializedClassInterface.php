<?php

namespace SoboLAN\RestNegotiator\Transformers;

interface SerializedClassInterface
{
    /**
     * To be removed when minimum phpversion >= 5.5
     * @see http://php.net/oop5.basic#language.oop5.basic.class.class
     */
    const CLASS_NAME = __CLASS__;

    public function getSerializedClass();
}
