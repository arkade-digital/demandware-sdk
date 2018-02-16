<?php

namespace Arkade\Demandware\Serializers;

use Arkade\Demandware\Entities;
use Carbon\Carbon;

class CustomerSerializer
{
    /**
     * Serialize.
     *
     * @param Entities\Customer $customer
     * @param bool $isNew
     * @return string
     */
    public function serialize(Entities\Customer $customer, $isNew = false)
    {
        // trigger the entities jsonSerialize method
        $serialized = json_decode(json_encode($customer));

        // demandware fields are in snake case
        $vars       = get_object_vars($serialized);
        $serialized = [];
        foreach ($vars as $key => $value) {
            $serialized[snake_case($key)] = $value;
        }

        // strip null value items
        $serialized = array_filter($serialized);

        if (isset($serialized['creation_date'])) unset($serialized['creation_date']);
        if (isset($serialized['last_modified'])) unset($serialized['last_modified']);
        if (isset($serialized['last_login_time'])) unset($serialized['last_login_time']);
        if (isset($serialized['last_visit_time'])) unset($serialized['last_visit_time']);
        if (isset($serialized['previous_login_time'])) unset($serialized['previous_login_time']);
        if (isset($serialized['previous_visit_time'])) unset($serialized['previous_visit_time']);

        if (isset($serialized['credentials'])) {
            $credentials = [];
            $vars        = get_object_vars($serialized['credentials']);
            foreach ($vars as $key => $value) {
                $credentials[snake_case($key)] = $value;
            }

            unset($credentials['locked']);
            unset($credentials['enabled']);

            $serialized['credentials'] = array_filter($credentials);
        }

        // convert address fields to snake case
        if (isset($serialized['primary_address'])) {
            $vars           = get_object_vars($serialized['primary_address']);
            $primaryAddress = [];
            foreach ($vars as $key => $value) {
                $primaryAddress[snake_case($key)] = $value;
            }

            $serialized['primary_address'] = array_filter($primaryAddress);
        }

        // append loyalty cartridge fields
        if (isset($serialized['loyalty_cartridge'])) {
            $vars             = get_object_vars($serialized['loyalty_cartridge']);
            $loyaltyCartridge = [];
            foreach ($vars as $key => $value) {
                $loyaltyCartridge['c_' . $key] = $value;
            }

            $loyaltyCartridge = array_filter($loyaltyCartridge);

            unset($serialized['loyalty_cartridge']);

            $serialized = array_merge($serialized, $loyaltyCartridge);
        }

        if (array_has($serialized, 'credentials.password')) {
            $password = $serialized['credentials']['password'];
            unset($serialized['credentials']['password']);
        }

        if ($isNew) {
            $serialized['login'] = $serialized['email'];

            unset($serialized['credentials'], $serialized['primary_address']);

            $serialized = [
                'password' => !empty($password) ? $password : '',
                'customer' => $serialized,
            ];
        }

        return json_encode($serialized);
    }
}