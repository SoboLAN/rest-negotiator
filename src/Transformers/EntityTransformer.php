<?php

namespace SoboLAN\RestNegotiator\Transformers;

use SoboLAN\RestNegotiator\Representation\EntityRepresentation;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class EntityTransformer extends SerializerAwareNormalizer implements NormalizerInterface, SerializedClassInterface
{
    public function getSerializedClass()
    {
        return EntityRepresentation::CLASS_NAME;
    }
    
    /**
     * {@inheritdoc}
     */
    public function normalize($representation, $format = null, array $context = array())
    {
        return array('results' => $this->serializer->normalize($representation->getEntity(), $format, $context));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof EntityRepresentation);
    }
}
