<?php

namespace Arkade\Demandware\Parsers;

use Carbon\Carbon;
use Arkade\Demandware\Entities;

class OrderParser
{
    /**
     * Parse the given array to a Customer entity.
     *
     * @param  array $payload
     * @return Customer
     */
    public function parse($payload)
    {
        $customer = (new Entities\Customer)
            ->setCreationDate(Carbon::parse((string) $payload->creation_date))
            ->setLastModified(Carbon::parse((string) $payload->last_modified))
            ->setCustomerId($payload->customer_id)
            ->setCustomerNo($payload->customer_no);

        if(!empty($payload->first_name)){
            $customer->setFirstName($payload->first_name);
        }

        if(!empty($payload->last_name)){
            $customer->setLastName($payload->last_name);
        }

        if(!empty($payload->email)){
            $customer->setEmail($payload->email);
        }

        if(!empty($payload->gender)){
            $customer->setGender($payload->gender);
        }

        if(!empty($payload->birthday)){
            $customer->setBirthday(Carbon::parse((string) $payload->birthday));
        }

        if(!empty($payload->company_name)){
            $customer->setCompanyName($payload->company_name);
        }

        if(!empty($payload->job_title)){
            $customer->setJobTitle($payload->job_title);
        }

        if(!empty($payload->phone_home)){
            $customer->setPhoneHome($payload->phone_home);
        }

        if(!empty($payload->phone_mobile)){
            $customer->setPhoneMobile($payload->phone_mobile);
        }

        if(!empty($payload->salutation)){
            $customer->setSalutation($payload->salutation);
        }

        if(!empty($payload->suffix)){
            $customer->setSuffix($payload->suffix);
        }

        if(!empty($payload->title)){
            $customer->setTitle($payload->title);
        }

        if(!empty($payload->credentials)){
            $customer->setCredentials(
                (new CredentialsParser)->parse($payload->credentials)
            );
        }

        if(!empty($payload->primary_address)){
            $customer->setPrimaryAddress(
                (new AddressParser)->parse($payload->primary_address)
            );
        }

        return $customer;
    }
}