<?php

namespace Arkade\Demandware\Serializers;

use Arkade\Demandware\Entities;

class AddressSerializer
{
    /**
     * Serialize.
     *
     * @param  Entities\Customer $address
     * @return string
     */
    public function serialize(Entities\Address $address)
    {
        // trigger the entities jsonSerialize method
        $serialized = json_decode(json_encode($address));

        // demandware fields are in snake case
        $vars = get_object_vars($serialized);
        $serialized = [];
        foreach ($vars as $key => $value){
            $serialized[snake_case($key)] = $value;
        }

        // strip null value items
        $serialized = array_filter($serialized);

        return json_encode($serialized);
    }
}