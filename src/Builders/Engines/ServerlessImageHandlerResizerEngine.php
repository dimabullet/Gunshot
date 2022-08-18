<?php

namespace BulletDigitalSolutions\Gunshot\Builders\Engines;

use BulletDigitalSolutions\Gunshot\Contracts\ResizerEngineContract;
use Illuminate\Support\Arr;

class ServerlessImageHandlerResizerEngine implements ResizerEngineContract
{
    /**
     * @var
     */
    private $cdnUrl;

    /**
     * @param $cdnUrl
     */
    public function __construct($cdnUrl)
    {
        $this->cdnUrl = $cdnUrl;
    }

    /**
     * @param $attributes
     * @param $path
     * @return string
     */
    public function toString($attributes, $path)
    {
        $url = $this->sanitizeCdnUrl($this->cdnUrl);

        $additionalParams = [];

        if ($resize = Arr::get($attributes, 'resize')) {
            if (Arr::get($resize, 'type')) {
                $additionalParams[] = Arr::get($resize, 'type');
            }
            $additionalParams[] = $resize['width'].'x'.$resize['height'];
        }

        if ($additionalParams) {
            $additionalParams = '/'.implode('/', $additionalParams).'/';
        } else {
            $additionalParams = '/';
        }

        $url = $url.$additionalParams.$path;

        return $url;
    }

    /**
     * @param $cdnUrl
     * @return mixed|string
     */
    public function sanitizeCdnUrl($cdnUrl)
    {
        if (strpos($cdnUrl, 'https://') !== 0) {
            $cdnUrl = 'https://'.$cdnUrl;
        }

        return $cdnUrl;
    }
}
