<?php

// Based on AWS Imagekit - https://www.youtube.com/watch?v=uVk-ffHeV7c

namespace BulletDigitalSolutions\Gunshot\Builders;

class ImageResizerBuilder
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var
     */
    private $resizerEngine;

    /**
     * @var string
     */
    private $path;

    public function __construct($path, $resizerEngine)
    {
        $this->resizerEngine = $resizerEngine;
        $this->path = $path;
        $this->attributes = [];
    }

    /**
     * @param $width
     * @param $height
     * @param $type
     * @return $this
     */
    public function resize($width, $height, $type = null)
    {
        $this->attributes['resize'] = [
            'width' => $width,
            'height' => $height,
            'type' => $type,
        ];

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
        return $this->getResizerEngine()->toString($this->attributes, $this->sanitizedPath());
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
