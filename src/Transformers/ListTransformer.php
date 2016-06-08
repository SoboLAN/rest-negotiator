<?php

namespace SoboLAN\RestNegotiator\Transformers;

use SoboLAN\RestNegotiator\Representation\ListRepresentation;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;

class ListTransformer extends SerializerAwareNormalizer implements NormalizerInterface, SerializedClassInterface
{
    public function getSerializedClass()
    {
        return ListRepresentation::CLASS_NAME;
    }
    
    /**
     * {@inheritdoc}
     */
    public function normalize($representation, $format = null, array $context = array())
    {
        $normalized = array(
            'results' => $this->serializer->normalize($representation->getItems(), $format, $context)
        );

        $pagination = $representation->getPagination();
        if (! is_null($pagination)) {
            $normalized['pagination'] = $pagination;
        }

        return $normalized;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof ListRepresentation);
    }
}
