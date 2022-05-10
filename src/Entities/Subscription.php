<?php

namespace BulletDigitalSolutions\Gunshot\Entities;

use Laravel\Cashier\Subscription as BaseSubscription;
use Doctrine\ORM\Mapping as ORM;

class Subscription extends BaseSubscription
{

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    protected $stripeId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $stripeStatus;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripePrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $trialEndsAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endsAt;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * @param mixed $stripeId
     */
    public function setStripeId($stripeId): void
    {
        $this->stripeId = $stripeId;
    }

    /**
     * @return mixed
     */
    public function getStripeStatus()
    {
        return $this->stripeStatus;
    }

    /**
     * @param mixed $stripeStatus
     */
    public function setStripeStatus($stripeStatus): void
    {
        $this->stripeStatus = $stripeStatus;
    }

    /**
     * @return mixed
     */
    public function getStripePrice()
    {
        return $this->stripePrice;
    }

    /**
     * @param mixed $stripePrice
     */
    public function setStripePrice($stripePrice): void
    {
        $this->stripePrice = $stripePrice;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getTrialEndsAt()
    {
        return $this->trialEndsAt;
    }

    /**
     * @param mixed $trialEndsAt
     */
    public function setTrialEndsAt($trialEndsAt): void
    {
        $this->trialEndsAt = $trialEndsAt;
    }

    /**
     * @return mixed
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * @param mixed $endsAt
     */
    public function setEndsAt($endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->getUser();
    }

}
