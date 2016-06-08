<?php

namespace SoboLAN\RestNegotiator\Version;

use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;

class VersionParser
{
    const KEY_TRANSFORMERS = 'transformers';
    const KEY_ENCODERS = 'encoders';

    /**
     * Reads the version value in the HTTP header identified by $determinedByHeaderName
     * and returns the transformers names found under that version value in the current route.
     * If the version is invalid, an Exception will be thrown.
     * If the current route has no such version defined, the default transformers names are returned.
     * Otherwise, an empty array is returned.
     * @param Request $request
     * @param string $determinedByHeaderName
     * @return array
     */
    public function getTransformersNamesByVersion(Request $request, $determinedByHeaderName)
    {
        return $this->getNamesByVersion(self::KEY_TRANSFORMERS, $request, $determinedByHeaderName);
    }

    /**
     * Reads the version value in the HTTP header identified by $determinedByHeaderName
     * and returns the encoders names found under that version value in the current route.
     * If the version is invalid, an Exception will be thrown.
     * If the current route has no such version defined, the default encoders names are returned.
     * Otherwise, an empty array is returned.
     * @param Request $request
     * @param string $determinedByHeaderName
     * @return array
     */
    public function getEncodersNamesByVersion(Request $request, $determinedByHeaderName)
    {
        return $this->getNamesByVersion(self::KEY_ENCODERS, $request, $determinedByHeaderName);
    }

    private function getNamesByVersion($name, Request $request, $determinedByHeaderName)
    {
        $version = $this->getVersion($request, $determinedByHeaderName);
        $versions = $request->get('versions');
        $defaultVersion = isset($versions['default_version']) ? $versions['default_version'] : null;

        if (! is_null($version) && isset($versions[$version][$name])) {
            return $versions[$version][$name];
        } elseif (! is_null($defaultVersion) && isset($versions[$defaultVersion][$name])) {
            return $versions[$defaultVersion][$name];
        } else {
            return array();
        }
    }

    private function getVersion(Request $request, $headerName)
    {
        if (! $request->headers->has($headerName)) {
            return null;
        }

        $acceptHeader = AcceptHeader::fromString($request->headers->get($headerName))->first();

        $version = $acceptHeader->getAttribute('version', null);

        if (! is_null($version)) {
            if (! ctype_digit((string) $version)) {
                throw new UnsupportedVersionException(
                    sprintf('Version %s is invalid', $version),
                    UnsupportedVersionException::CODE
                );
            }

            return ((int) $version);
        }

        return null;
    }
}
