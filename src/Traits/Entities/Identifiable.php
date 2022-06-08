<?php

namespace BulletDigitalSolutions\Gunshot\Traits\Entities;

use Webpatser\Uuid\Uuid;

trait Identifiable
{
    /**
     * @ORM\Column(type="string", length=36, unique=true)
     */
    protected $uuid;

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function assignUuid()
    {
        $this->setUuid(Uuid::generate()->string);
    }

}
