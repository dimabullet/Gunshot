<?php

namespace BulletDigitalSolutions\Gunshot\Contracts\Repositories;

interface PivotRepositoryContract
{
    public function getParentGetter();

    public function getChildGetter();

    public function getParentSetter();

    public function getChildSetter();

    public function getParentName();

    public function getChildName();

    /**
     * @param $pivot
     * @param $attributes
     * @return mixed
     */
    public function savePivotAttributes($pivot, $attributes = []);
}
