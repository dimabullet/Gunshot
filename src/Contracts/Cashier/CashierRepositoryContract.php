<?php

//TODO - This is not required anymore

namespace BulletDigitalSolutions\Gunshot\Contracts\Cashier;

use BulletDigitalSolutions\Gunshot\Traits\Cashier\CashierRepository;

interface CashierRepositoryContract
{
    /**
     * @param $entity
     * @param $stripeId
     * @return void
     */
    public function setStripeId($entity, $stripeId);

    /**
     * @param  \Laravel\Cashier\PaymentMethod|\Stripe\PaymentMethod|null  $paymentMethod
     * @return $this
     */
    public function fillPaymentMethodDetails($entity, $paymentMethod);

    /**
     * @param $entity
     * @param $attributes
     * @return void
     */
    public function forceFillPaymentMethodDetails($entity, $attributes = []);
}