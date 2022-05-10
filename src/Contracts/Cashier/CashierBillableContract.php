<?php

namespace BulletDigitalSolutions\Gunshot\Contracts\Cashier;

interface CashierBillableContract
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getRepository();
}