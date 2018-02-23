<?php

namespace Arkade\Demandware\Factories;

use Arkade\Demandware\Entities\Address;
use Arkade\Demandware\Entities\Credentials;
use Arkade\Demandware\Entities\LoyaltyCartridge;
use Arkade\Demandware\Entities\Customer;
use Carbon\Carbon;

class CustomerFactory
{
    /**
     * Make an customer entity.
     *
     * @return Entities\Customer
     */
    public function make()
    {
        $customerAddress = new Address();
        $customerAddress->setFullName('Andy Johnston');
        $customerAddress->setLastName('Johnston');
        $customerAddress->setFirstName('Andy');
        $customerAddress->setAddress1('10 Foo St');
        $customerAddress->setAddress2('Something');
        $customerAddress->setSuite('4');
        $customerAddress->setAddressId('Billing');
        $customerAddress->setCity('Melbourne');
        $customerAddress->setCompanyName('Arkade');
        $customerAddress->setCountryCode('AU');
        $customerAddress->setJobTitle('Dev');
        $customerAddress->setPhone('33334444');
        $customerAddress->setPostalCode('3000');
        $customerAddress->setPostBox('453333');
        $customerAddress->setSalutation('Mr');
        $customerAddress->setTitle('Dr');
        $customerAddress->setSuffix('FRGS');

        $customerCredentials = new Credentials();
        $customerCredentials->setEnabled(true);
        $customerCredentials->setLocked(false);
        $customerCredentials->setLogin('andy+unit3@arkade.com.au');
        $customerCredentials->setPassword('testing');
        $customerCredentials->setPasswordQuestion('chicken or egg');

        $customerLoyaltyCartridge = new LoyaltyCartridge();
        $customerLoyaltyCartridge->setCurrentRewardBalance(200);
        $customerLoyaltyCartridge->setCurrentTierMaintainDeadline(Carbon::now()->addYear(1));
        $customerLoyaltyCartridge->setNextTierMaintainDeadline(Carbon::now()->addYear(1));
        $customerLoyaltyCartridge->setPrevTierOfMember('Silver');
        $customerLoyaltyCartridge->setCurrentTierOfMember('Gold');
        $customerLoyaltyCartridge->setNextTierOfMember('Platinum');
        $customerLoyaltyCartridge->setNextTierSpendProgress('100');
        $customerLoyaltyCartridge->setNextTierSpendRequired('400');
        $customerLoyaltyCartridge->setOmneoMemberID('10001');
        $customerLoyaltyCartridge->setOmneoResourceID('85');
        $customerLoyaltyCartridge->setSpendProgress('100');
        $customerLoyaltyCartridge->setSpendRequired('400');

        $customer = new Customer();
        $customer->setLastName('Johnston');
        $customer->setFirstName('Andy');
        $customer->setJobTitle('Dev');
        $customer->setSalutation('Mr');
        $customer->setTitle('Dr');
        $customer->setSuffix('FRGS');
        $customer->setCompanyName('Arkade');
        $customer->setEmail('andy+unit3@arkade.com.au');
        $customer->setBirthday(Carbon::now()->subYears(40));
        $customer->setGender(1);
        $customer->setPhoneHome('11112222');
        $customer->setPhoneMobile('1111222333');

        $customer->setPrimaryAddress($customerAddress);
        $customer->setCredentials($customerCredentials);
        $customer->setLoyaltyCartridge($customerLoyaltyCartridge);

        return $customer;
    }
}