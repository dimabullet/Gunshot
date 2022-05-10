<?php

namespace BulletDigitalSolutions\Gunshot\Traits\Cashier;

use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesCustomer;
use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesPaymentMethods;
use Laravel\Cashier\Concerns\HandlesTaxes;
use Laravel\Cashier\Concerns\ManagesInvoices;
use Laravel\Cashier\Concerns\ManagesSubscriptions;
use Laravel\Cashier\Concerns\PerformsCharges;
use Doctrine\ORM\Mapping as ORM;

trait Billable
{
    use HandlesTaxes;
    use ManagesCustomer;
    use ManagesInvoices;
    use ManagesPaymentMethods;
    use ManagesSubscriptions;
    use PerformsCharges;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripeId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pmType;

    /**
     * @ORM\Column(type="string", nullable=true, length=4)
     */
    protected $pmLastFour;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $trialEndsAt;

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
    public function getPmType()
    {
        return $this->pmType;
    }

    /**
     * @param mixed $pmType
     */
    public function setPmType($pmType): void
    {
        $this->pmType = $pmType;
    }

    /**
     * @return mixed
     */
    public function getPmLastFour()
    {
        return $this->pmLastFour;
    }

    /**
     * @param mixed $pmLastFour
     */
    public function setPmLastFour($pmLastFour): void
    {
        $this->pmLastFour = $pmLastFour;
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

}
