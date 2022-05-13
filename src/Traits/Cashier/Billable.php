<?php

//TODO - This is not required anymore

namespace BulletDigitalSolutions\Gunshot\Traits\Cashier;

use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesCustomer;
use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesPaymentMethods;
use Laravel\Cashier\Concerns\HandlesTaxes;
use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesInvoices;
use BulletDigitalSolutions\Gunshot\Concerns\Cashier\ManagesSubscriptions;
use BulletDigitalSolutions\Gunshot\Concerns\Cashier\PerformsCharges;
use Doctrine\ORM\Mapping as ORM;

trait Billable
{
    use HandlesTaxes;
    use ManagesCustomer;
    use ManagesInvoices;
    use ManagesPaymentMethods;
    use ManagesSubscriptions;
    use PerformsCharges;

}
