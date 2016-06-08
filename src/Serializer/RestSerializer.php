<?php

namespace SoboLAN\RestNegotiator\Serializer;

use SoboLAN\RestNegotiator\Transformers\SerializedClassInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class RestSerializer extends Serializer implements RestSerializerInterface
{
    public function __construct(array $transformers = array(), array $encoders = array())
    {
        foreach ($transformers as $transformer) {
            if (! ($transformer instanceof SerializedClassInterface)) {
                $message = sprintf(
                    'Transformer %s must implement %s',
                    get_class($transformer),
                    SerializedClassInterface::CLASS_NAME
                );
                throw new UnexpectedValueException($message);
            }
        }

        parent::__construct($transformers, $encoders);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceTransformers(array $newTransformers)
    {
        /* @var $newTransformer SerializedClassInterface */
        foreach ($newTransformers as $newTransformer) {
            /* @var $normalizer SerializedClassInterface */
            foreach ($this->normalizers as $index => $normalizer) {
                if ($normalizer->getSerializedClass() == $newTransformer->getSerializedClass()) {
                    unset($this->normalizers[$index]);
                    break;
                }
            }

            if ($newTransformer instanceof SerializerAwareInterface) {
                $newTransformer->setSerializer($this);
            }

            array_push($this->normalizers, $newTransformer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformers()
    {
        return $this->normalizers;
    }

    /**
     * {@inheritdoc}
     */
    public function setTransformers(array $transformers)
    {
        $this->normalizers = $transformers;
    }
}
