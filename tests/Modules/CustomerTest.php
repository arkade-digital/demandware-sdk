<?php

namespace Arkade\Demandware\Modules;

use GuzzleHttp\Psr7\Response;
use Arkade\Demandware\Factories\CustomerFactory;
use PHPUnit\Framework\TestCase;
use Arkade\Demandware\InteractsWithClient;
use Arkade\Demandware\Entities\Customer;
use Illuminate\Support\Collection;

class CustomerTest extends TestCase
{
    use InteractsWithClient;

    /**
     * @test
     */
    public function customer_find_by_id()
    {
        // Create the container.
        $history = [];

        // Create a mock response object.
        $client = $this->createClient([
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_find_by_id.json')
            )
        ], $history);

        $response = $client->customers()->findById('00026501');

        $this->assertInstanceOf(Customer::class, $response);

    }

    /**
     * @test
     */
    public function customer_find_by_email()
    {
        // Create the container.
        $history = [];

        // Create a mock response object.
        $client = $this->createClient([
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_find_by_email.json')
            ),
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_find_by_id.json')
            ),
        ], $history);

        $response = $client->customers()->findByEmail('andy@arkade.com.au');

        $this->assertInstanceOf(Customer::class, $response);

    }

    /**
     * @test
     */
    public function customer_create()
    {
        // Create the container.
        $history = [];

        // Create a mock response object.
        $client = $this->createClient([
            new Response(200, ['Content-Type' => 'application/json', 'Authorization' => 'Bearer eyJfdiI6IjEiLCJhbGciOiJSU'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_auth.json')
            ),
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_create.json')
            ),
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/address_create.json')
            ),
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_create_response.json')
            ),
        ], $history);

        $customer = (new CustomerFactory())->make();
        $response = $client->customers()->create($customer);

        $this->assertInstanceOf(Customer::class, $response);

    }

    /**
     * @test
     */
    public function customer_update()
    {
        // Create the container.
        $history = [];

        // Create a mock response object.
        $client = $this->createClient([
            new Response(200, ['Content-Type' => 'application/json'],
                file_get_contents(__DIR__.'../../Stubs/Customers/customer_update.json')
            ),
        ], $history);

        $customer = (new CustomerFactory())->make();
        $response = $client->customers()->update($customer);

        $this->assertInstanceOf(Customer::class, $response);

    }

}