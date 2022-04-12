<?php

namespace BulletDigitalSolutions\Gunshot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BulletDigitalSolutions\Gunshot\Skeleton\SkeletonClass
 */
class GunshotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gunshot';
    }
}
