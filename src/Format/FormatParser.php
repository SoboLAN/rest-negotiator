<?php

namespace SoboLAN\RestNegotiator\Format;

use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class FormatParser
{
    const DEFAULT_FORMAT = JsonEncoder::FORMAT;
    
    /**
     * Attempts to retrieve the format from the header identified by $headerName which is found in the $request object.
     * A default value will be returned if the format can not be retrieved
     * @param Request $request
     * @param string $headerName
     * @return string
     */
    public function getFormat(Request $request, $headerName)
    {
        $format = $this->parseFormatFromHeader($request, $headerName);
        
        return $format;
    }
    
    /**
     * Attempts to retrieve the format from the header identified by $headerName which is found in the $request object.
     * A default value will be returned if the format can not be retrieved.
     * As opposed to the getFormat() method, this method will also check if $serializer supports the retrieved
     * format. If it doesn't, an exception is thrown.
     * @param Request $request
     * @param string $headerName
     * @param EncoderInterface $serializer
     * @return string
     */
    public function getSupportedFormat(Request $request, $headerName, EncoderInterface $serializer)
    {
        $format = $this->parseFormatFromHeader($request, $headerName);
        
        if (! $serializer->supportsEncoding($format)) {
            throw new UnsupportedFormatException(
                sprintf('Format %s is unsupported', $format),
                UnsupportedFormatException::CODE
            );
        }
        
        return $format;
    }
    
    private function parseFormatFromHeader(Request $request, $headerName)
    {
        if (! $request->headers->has($headerName)) {
            //if there is no header with the specified name, fall-back on the default format
            return self::DEFAULT_FORMAT;
        }
        
        $acceptHeader = AcceptHeader::fromString($request->headers->get($headerName))->first();
        
        $format = $acceptHeader->getAttribute('format', null);
        
        return (is_null($format) ? self::DEFAULT_FORMAT : $format);
    }
}
