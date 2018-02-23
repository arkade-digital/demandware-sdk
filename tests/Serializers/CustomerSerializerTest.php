<?php

namespace Arkade\Demandware\Serializers;

use PHPUnit\Framework\TestCase;
use Arkade\Demandware\Factories;

class CustomerSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function returns_populated_json()
    {
        $json = (new CustomerSerializer)->serialize(
            (new Factories\CustomerFactory)->make()
        );

        $this->assertEquals(file_get_contents(__DIR__.'/../Stubs/Customers/create_customer_request.json'), $json);
    }
}