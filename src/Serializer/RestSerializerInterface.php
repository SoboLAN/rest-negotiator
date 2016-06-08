<?php

namespace SoboLAN\RestNegotiator\Serializer;

interface RestSerializerInterface
{
    /**
     * Takes the new transformers specified as parameter and sets them in the set of available transformers.
     * If a transformer already exists that handles the same representation,
     * the old one is removed and replaced with the new one.
     * @param array $newTransformers
     */
    public function replaceTransformers(array $newTransformers);

    /**
     * Returns the set of transformers currently loaded in this serializer
     * @return array
     */
    public function getTransformers();

    /**
     * Replaces the current set of transformers with the ones specified as parameter
     * @param array $transformers
     */
    public function setTransformers(array $transformers);
}
