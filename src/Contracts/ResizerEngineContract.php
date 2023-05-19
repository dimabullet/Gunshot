<?php

namespace BulletDigitalSolutions\Gunshot\Contracts;

interface ResizerEngineContract
{

    /**
     * @param string $path
     * @return string
     */
    public function toString(string $path): string;

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value): self;

}
