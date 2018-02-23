<?php

namespace Arkade\Demandware\Parsers;

use Carbon\Carbon;
use Arkade\Demandware\Entities\Address;
use Arkade\Demandware\Entities\Credentials;
use Arkade\Demandware\Entities\LoyaltyCartridge;
use Arkade\Demandware\Entities\Customer;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class CustomerParserTest extends TestCase
{
    /**
     * @test
     */
    public function returns_populated_order()
    {
        $customer = (new CustomerParser)->parse(
            json_decode(file_get_contents(__DIR__.'/../Stubs/Customers/customer_find_by_id.json'))
        );

        $this->assertInstanceOf(Customer::class, $customer);

        $this->assertEquals('abuKoZG0wlU07sox3RboOFtrRt', $customer->getCustomerId());
        $this->assertEquals('00026501', $customer->getCustomerNo());
        $this->assertEquals('Andy', $customer->getFirstName());
        $this->assertEquals('Johnston', $customer->getLastName());
        $this->assertEquals('andy@arkade.com.au', $customer->getEmail());
        $this->assertEquals(1, $customer->getGender());
        $this->assertEquals(Carbon::parse('2018-02-16'), $customer->getBirthday());
        $this->assertEquals(Carbon::parse('2018-02-16 03:25:47.000000'), $customer->getCreationDate());
        $this->assertEquals(Carbon::parse('2018-02-16 04:33:01.000000'), $customer->getLastModified());
        $this->assertEquals(Carbon::parse('2018-02-16 03:27:10.000000'), $customer->getLastLoginTime());
        $this->assertEquals(Carbon::parse('2018-02-16 03:27:10.000000'), $customer->getLastVisitTime());
        $this->assertEquals(Carbon::parse('2018-02-16 03:27:10.000000'), $customer->getPreviousLoginTime());
        $this->assertEquals(Carbon::parse('2018-02-16 03:27:10.000000'), $customer->getPreviousVisitTime());

    }

    /**
     * @test
     */
    public function returns_populated_credentials()
    {
        $customer = (new CustomerParser)->parse(
            json_decode(file_get_contents(__DIR__.'/../Stubs/Customers/customer_find_by_id.json'))
        );

        $this->assertInstanceOf(Credentials::class, $customer->getCredentials());

        $loyaltyCartridge = $customer->getCredentials();

        $this->assertEquals(false, $loyaltyCartridge->isEnabled());
        $this->assertEquals(false, $loyaltyCartridge->isLocked());
        $this->assertEquals('andy@arkade.com.au', $loyaltyCartridge->getLogin());
        $this->assertEquals(null, $loyaltyCartridge->getPassword());
        $this->assertEquals(null, $loyaltyCartridge->getPasswordQuestion());

    }

    /**
     * @test
     */
    public function returns_populated_loyalty_cartridge()
    {
        $customer = (new CustomerParser)->parse(
            json_decode(file_get_contents(__DIR__.'/../Stubs/Customers/customer_find_by_id.json'))
        );

        $this->assertInstanceOf(LoyaltyCartridge::class, $customer->getLoyaltyCartridge());

        $loyaltyCartridge = $customer->getLoyaltyCartridge();

        $this->assertEquals(null, $loyaltyCartridge->getCurrentRewardBalance());
        $this->assertEquals(Carbon::parse('2019-02-16 00:00:00.000000'), $loyaltyCartridge->getCurrentTierMaintainDeadline());
        $this->assertEquals(Carbon::parse('2019-02-16 00:00:00.000000'), $loyaltyCartridge->getNextTierMaintainDeadline());
        $this->assertEquals('T0', $loyaltyCartridge->getCurrentTierOfMember());
        $this->assertEquals('T1', $loyaltyCartridge->getNextTierOfMember());
        $this->assertEquals('0.0', $loyaltyCartridge->getNextTierSpendProgress());
        $this->assertEquals('500', $loyaltyCartridge->getNextTierSpendRequired());
        $this->assertEquals('281400', $loyaltyCartridge->getOmneoMemberID());
        $this->assertEquals('17', $loyaltyCartridge->getOmneoResourceID());

    }

}
