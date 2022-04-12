<?php

namespace BulletDigitalSolutions\Gunshot\Entities\Traits;

trait Activatable
{

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    protected $isActive = true;

    /**
     * @return boolean
     */
    public function isActive()
    {
        return 1 == $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

}
