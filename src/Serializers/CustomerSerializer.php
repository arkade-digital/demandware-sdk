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

        // strip null value items
        $serialized = array_filter($serialized);

        // convert credentials fields to snake case
        if(isset($serialized['credentials'])){
            $vars = get_object_vars($serialized['credentials']);
            $credentials = [];
            foreach ($vars as $key => $value){
                $credentials[snake_case($key)] = $value;
            }

            unset($credentials['locked']);
            unset($credentials['enabled']);

            $serialized['credentials'] = array_filter($credentials);
        }

        // convert address fields to snake case
        if(isset($serialized['primary_address'])){
            $vars = get_object_vars($serialized['primary_address']);
            $primaryAddress = [];
            foreach ($vars as $key => $value){
                $primaryAddress[snake_case($key)] = $value;
            }

            $serialized['primary_address'] = array_filter($primaryAddress);
        }

        // remove id field
        unset($serialized['id']);

        dump($serialized);

        return json_encode($serialized);
    }
}