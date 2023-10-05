<?php

namespace BulletDigitalSolutions\Gunshot\Builders\Engines;

use BulletDigitalSolutions\Gunshot\Contracts\ResizerEngineContract;
use Illuminate\Support\Arr;

class ServerlessImageHandlerResizerEngine implements ResizerEngineContract
{
    /**
     * @var string
     */
    protected string $cdnUrl;

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @param string $cdnUrl
     */
    public function __construct(string $cdnUrl)
    {
        $this->cdnUrl = $cdnUrl;
    }

    /**
     * @param string $path
     * @return string
     */
    public function toString(string $path): string
    {
        $url = $this->sanitizeCdnUrl($this->cdnUrl);

        $additionalParams = [];

        if ($resize = Arr::get($this->attributes, 'resize')) {
            if (Arr::get($resize, 'type')) {
                $additionalParams[] = Arr::get($resize, 'type');
            }
            $additionalParams[] = $resize['width'].'x'.$resize['height'];
        }

        if ($fileFormat = Arr::get($this->attributes, 'fileFormat')) {
            $additionalParams[] = 'filters:format(' . $fileFormat . ')';
        }

        if (Arr::get($this->attributes, 'stripExif', false)) {
            $additionalParams[] = 'filters:strip_exif()';
        }

        if ($additionalParams) {
            $additionalParams = '/'.implode('/', $additionalParams).'/';
        } else {
            $additionalParams = '/';
        }

        return rtrim($url, '/').$additionalParams.$path;
    }

    /**
     * @param string $cdnUrl
     * @return string
     */
    public function sanitizeCdnUrl(string $cdnUrl)
    {
        if (strpos($cdnUrl, 'https://') !== 0) {
            $cdnUrl = 'https://'.$cdnUrl;
        }

        return $cdnUrl;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

}
