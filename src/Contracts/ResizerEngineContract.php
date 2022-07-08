<?php

namespace BulletDigitalSolutions\Gunshot\Contracts;

interface ResizerEngineContract
{
    /**
     * @return string
     */
    public function toString(array $filters, $path);
}
