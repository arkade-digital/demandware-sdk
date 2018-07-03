<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Entities\Customer;
use Arkade\Demandware\Exceptions\TokenNotFoundException;
use Arkade\Demandware\Exceptions\UnexpectedException;
use Illuminate\Support\Collection;

Class Authentication Extends AbstractModule
{

    /**
     * @param Customer $customer
     * @return Customer
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function authenticate(Customer $customer)
    {

    }

}