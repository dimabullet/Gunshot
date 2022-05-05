<?php

namespace BulletDigitalSolutions\Gunshot\Contracts;

interface QueryEngineContract
{
    /**
     * @return string
     */
    public function toString(array $filters);
}