<?php

namespace SoboLAN\RestNegotiator\Negotiator;

use SoboLAN\RestNegotiator\Representation\RepresentationInterface;
use SoboLAN\RestNegotiator\Serializer\RestSerializer;
use SoboLAN\RestNegotiator\Factory\RestSerializerFactory;
use SoboLAN\RestNegotiator\Version\VersionParser;
use SoboLAN\RestNegotiator\Format\FormatParser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class RestNegotiator
{
    /**
     * @var RestSerializerFactory
     */
    private $serializerFactory;

    /**
     * @var RestSerializer
     */
    private $serializer;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var VersionParser
     */
    private $versionParser;

    /**
     * @var FormatParser
     */
    private $formatParser;

    /**
     * @var array
     */
    private $defaultTransformersNames;

    public function __construct(
        RequestStack $requestStack,
        RestSerializerFactory $serializerFactory,
        VersionParser $versionParser,
        FormatParser $formatParser,
        array $defaultTransformersNames,
        array $defaultEncodersNames
    ) {
        $this->requestStack = $requestStack;
        $this->serializerFactory = $serializerFactory;
        $this->versionParser = $versionParser;
        $this->formatParser = $formatParser;
        $this->defaultTransformersNames = $defaultTransformersNames;

        $this->serializer = $serializerFactory->buildDefaultSerializer(
            $defaultTransformersNames,
            $defaultEncodersNames
        );
    }

    public function getResponse(
        RepresentationInterface $representation,
        $statusCode,
        array $headers = array(),
        array $context = array()
    ) {
        $request = $this->requestStack->getCurrentRequest();

        $this->prepareSerializer($request, 'Accept');

        $format = $this->formatParser->getSupportedFormat($request, 'Accept', $this->serializer);
        $request->setRequestFormat($format);

        //make the actual serialization
        $serializedData = $this->serializer->serialize($representation, $format, $context);

        $this->restoreDefaultTransformers();

        //build and return the response
        $response = new Response($serializedData, $statusCode, $headers);
        $response->prepare($request);

        return $response;
    }

    public function getDeserialized($className)
    {
        $request = $this->requestStack->getCurrentRequest();

        $this->prepareSerializer($request, 'Content-Type');

        $format = $this->formatParser->getSupportedFormat($request, 'Content-Type', $this->serializer);

        //make the actual deserialization
        $data = $request->getContent();
        $deserializedData = $this->serializer->deserialize($data, $className, $format);

        $this->restoreDefaultTransformers();

        return $deserializedData;
    }

    private function prepareSerializer(Request $request, $headerName)
    {
        //if a version is specified that adds/overwrites some of the transformers, this is where it's done
        $overwrittenTransformers = $this->versionParser->getTransformersNamesByVersion($request, $headerName);
        $this->serializerFactory->overwriteTransformers($this->serializer, $overwrittenTransformers);
    }

    private function restoreDefaultTransformers()
    {
        /**
         * If a subrequest is made or if the {@link getResponse} method is called,
         * then the transformers overwritten above can not be present within the Serializer.
         *
         * This is because there may be a different version value in the "Accept" header,
         * a version which does not overwrite all the transformers which were overwritten above.
         * It would lead to inconsistent / incorrect outputs.
         *
         * In order to solve this, the default transformers are restored to the Serializer.
         * This way, when a subrequest or a call to  {@link getResponse} is made, those executions
         * will have the freedom to overwrite their own transformers.
         */
        $this->serializerFactory->overwriteTransformers($this->serializer, $this->defaultTransformersNames);
    }
}
