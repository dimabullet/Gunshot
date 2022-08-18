<?php

namespace BulletDigitalSolutions\Gunshot\Contracts\Repositories;

interface PivotRepositoryContract
{
    /**
     * @param $pivot
     * @param $attributes
     * @return mixed
     */
    public function savePivotAttributes($pivot, $attributes = []);
}