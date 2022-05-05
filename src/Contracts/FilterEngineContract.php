<?php

namespace BulletDigitalSolutions\Gunshot\Contracts;

interface FilterEngineContract
{
    /**
     * @return string
     */
    public function toString(array $filters);
}