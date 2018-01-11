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

        // demandware fields are in snake case
        $vars = get_object_vars($serialized);
        $serialized = [];
        foreach ($vars as $key => $value){
            $serialized[snake_case($key)] = $value;
        }

        // convert credentials fields to snake case
        if(isset($serialized['credentials'])){
            $vars = get_object_vars($serialized['credentials']);
            $serialized['credentials'] = [];
            foreach ($vars as $key => $value){
                $serialized['credentials'][snake_case($key)] = $value;
            }

            unset($serialized['credentials']['locked']);
            unset($serialized['credentials']['enabled']);
        }

        // strip null value items
        $serialized = array_filter($serialized);

        // remove id field
        unset($serialized['id']);

        return json_encode($serialized);
    }
}