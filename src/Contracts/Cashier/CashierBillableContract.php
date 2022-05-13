<?php

//TODO - This is not required anymore

namespace BulletDigitalSolutions\Gunshot\Contracts\Cashier;

interface CashierBillableContract
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getRepository();

    /**
     * @return mixed
     */
    public function getSubscriptions();

}