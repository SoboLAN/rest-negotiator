<?php

namespace SoboLAN\RestNegotiator\Transformers;

use SoboLAN\RestNegotiator\Representation\Representation;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class RepresentationTransformer extends SerializerAwareNormalizer implements
    NormalizerInterface,
    SerializedClassInterface
{
    public function getSerializedClass()
    {
        return Representation::CLASS_NAME;
    }
    
    /**
     * {@inheritdoc}
     */
    public function normalize($representation, $format = null, array $context = array())
    {
        return $this->serializer->normalize($representation->getItems(), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof Representation);
    }
}
