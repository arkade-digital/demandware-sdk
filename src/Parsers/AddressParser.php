<?php

namespace Arkade\Demandware\Parsers;

use Carbon\Carbon;
use Arkade\Demandware\Entities;

class AddressParser
{
    /**
     * Parse the given array to a Address entity.
     *
     * @param  array $payload
     * @return Address
     */
    public function parse($payload)
    {
        $address = new Entities\Address();

        if(!empty($payload->address_id)){
            $address->setAddressId($payload->address_id);
        }

        if(!empty($payload->address1)){
            $address->setAddress1($payload->address1);
        }

        if(!empty($payload->address2)){
            $address->setAddress2($payload->address2);
        }

        if(!empty($payload->city)){
            $address->setCity($payload->city);
        }

        if(!empty($payload->companyName)){
            $address->setCompanyName($payload->companyName);
        }

        if(!empty($payload->countryCode)){
            $address->setCountryCode($payload->countryCode);
        }

        if(!empty($payload->first_name)){
            $address->setFirstName($payload->first_name);
        }

        if(!empty($payload->full_name)){
            $address->setFullName($payload->full_name);
        }

        if(!empty($payload->job_title)){
            $address->setJobTitle($payload->job_title);
        }

        if(!empty($payload->last_name)){
            $address->setLastName($payload->last_name);
        }

        if(!empty($payload->phone)){
            $address->setPhone($payload->phone);
        }

        if(!empty($payload->post_box)){
            $address->setPostBox($payload->post_box);
        }

        if(!empty($payload->postal_code)){
            $address->setPostalCode($payload->postal_code);
        }

        if(!empty($payload->salutation)){
            $address->setSalutation($payload->salutation);
        }

        if(!empty($payload->state_code)){
            $address->setStateCode($payload->state_code);
        }

        if(!empty($payload->suffix)){
            $address->setSuffix($payload->suffix);
        }

        if(!empty($payload->suite)){
            $address->setSuite($payload->suite);
        }

        if(!empty($payload->title)){
            $address->setTitle($payload->title);
        }

        return $address;
    }
}