<?php

// Based on AWS Imagekit - https://www.youtube.com/watch?v=uVk-ffHeV7c

namespace BulletDigitalSolutions\Gunshot\Builders;

use BulletDigitalSolutions\Gunshot\Contracts\ResizerEngineContract;

class ImageResizerBuilder
{

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var ResizerEngineContract
     */
    protected ResizerEngineContract $resizerEngine;

    public function __construct(string $path, ResizerEngineContract $resizerEngine)
    {
        $this->path = $path;
        $this->resizerEngine = $resizerEngine;
    }

    /**
     * @param $width
     * @param $height
     * @param $type
     * @return $this
     */
    public function resize($width, $height, $type = null): self
    {
        $this->resizerEngine->setAttribute('resize', [
            'width' => $width,
            'height' => $height,
            'type' => $type,
        ]);

        return $this;
    }

    /**
     * Will allow for filter to be passed such as filters:format(jpeg) where jpeg is the $format
     *
     * @param string $format
     * @return $this
     */
    public function fileFormat(string $format): self
    {
        $this->resizerEngine->setAttribute('fileFormat', $format);

        return $this;
    }

    /**
     * @return $this
     */
    public function stripExif(): self
    {
        $this->resizerEngine->setAttribute('stripExif', true);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResizerEngine()
    {
        return $this->resizerEngine;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this
            ->getResizerEngine()
            ->toString($this->sanitizedPath())
        ;
    }

    /**
     * @param $path
     * @return mixed|string
     */
    public function sanitizedPath()
    {
        // Remove / if there
        if (strpos($this->path, '/') === 0) {
            $this->path = substr($this->path, 1);
        }

        return $this->path;
    }
}
