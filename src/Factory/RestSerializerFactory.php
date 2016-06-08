<?php

namespace SoboLAN\RestNegotiator\Factory;

use SoboLAN\RestNegotiator\Serializer\RestSerializer;
use SoboLAN\RestNegotiator\Serializer\RestSerializerInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RestSerializerFactory implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $defaultTransformers;

    /**
     * @var array
     */
    private $defaultEncoders;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Creates and returns an instance of MeeticSerializer using the provided transformers and encoders names
     * @param array $defaultTransformersNames
     * @param array $defaultEncodersNames
     * @return RestSerializer
     */
    public function buildDefaultSerializer(array $defaultTransformersNames, array $defaultEncodersNames)
    {
        $transformers = $this->buildDefaultTransformers($defaultTransformersNames);
        $encoders = $this->buildDefaultEncoders($defaultEncodersNames);

        return new RestSerializer($transformers, $encoders);
    }

    /**
     * Overwrites the specified serialized with the transformers specified.
     * @param MeeticSerializerInterface $serializer
     * @param array $newTransformersNames the names of the services that represent the transformers to be overwritten
     */
    public function overwriteTransformers(RestSerializerInterface $serializer, $newTransformersNames)
    {
        $newTransformers = array();
        foreach ($newTransformersNames as $newTransformerName) {
            array_push($newTransformers, $this->container->get($newTransformerName));
        }

        $serializer->replaceTransformers($newTransformers);
    }

    private function buildDefaultTransformers($defaultTransformersNames)
    {
        if (! is_null($this->defaultTransformers)) {
            return $this->defaultTransformers;
        }

        $this->defaultTransformers = array();
        foreach ($defaultTransformersNames as $transformerName) {
            array_push($this->defaultTransformers, $this->container->get($transformerName));
        }

        return $this->defaultTransformers;
    }

    private function buildDefaultEncoders($defaultEncodersNames)
    {
        if (! is_null($this->defaultEncoders)) {
            return $this->defaultEncoders;
        }

        $this->defaultEncoders = array();
        foreach ($defaultEncodersNames as $encoderName) {
            array_push($this->defaultEncoders, $this->container->get($encoderName));
        }

        return $this->defaultEncoders;
    }
}
