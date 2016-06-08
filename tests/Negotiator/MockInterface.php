<?php

namespace SoboLAN\RestNegotiator\Tests\Negotiator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

use SoboLAN\RestNegotiator\Serializer\RestSerializerInterface;

interface MockInterface extends SerializerInterface, EncoderInterface, RestSerializerInterface
{
}
