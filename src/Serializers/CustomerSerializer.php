<?php

namespace Arkade\Demandware\Serializers;

use Arkade\Demandware\Entities;

class CustomerSerializer
{
    /**
     * Serialize.
     *
     * @param  Entities\Customer $customer
     * @return string
     */
    public function serialize(Entities\Customer $customer)
    {
        // trigger the entities jsonSerialize method
        $serialized = json_decode(json_encode($customer));

        return json_encode($serialized);
    }
}