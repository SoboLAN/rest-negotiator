<?php

namespace SoboLAN\RestNegotiator\Tests\Representation;

use SoboLAN\RestNegotiator\Representation\ExceptionRepresentation;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExceptionRepresentationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetter()
    {
        $exception = new BadRequestHttpException();

        $representation = new ExceptionRepresentation($exception);

        $result = $representation->getException();

        $this->assertEquals($result, $exception);
    }
}
