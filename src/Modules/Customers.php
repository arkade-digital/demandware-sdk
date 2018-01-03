<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Modules\AbstractModule;

Class Customers Extends AbstractModule
{

    public function getCustomer($id)
    {
        return $this->client->get("customer_lists/{$this->getSiteName()}/customers/{$id}");
    }
}