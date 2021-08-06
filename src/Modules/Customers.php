<?php

namespace Arkade\Demandware\Modules;

use Illuminate\Support\Collection;
use Arkade\Demandware\Entities\Customer;
use Arkade\Demandware\Parsers\CustomerParser;
use Arkade\Demandware\Serializers\AddressSerializer;
use Arkade\Demandware\Serializers\CustomerSerializer;
use Arkade\Demandware\Serializers\CustomerListCustomerSerializer;
use Arkade\Demandware\Exceptions\UnexpectedException;
use Arkade\Demandware\Exceptions\TokenNotFoundException;

Class Customers Extends AbstractModule
{
    /**
     * @param $id
     * @return Customer
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function findById($id)
    {
        return (new CustomerParser)->parse(
            $this->client->get(
                $this->useData("customer_lists/{$this->getSiteName()}/customers/{$id}")
            )
        );
    }

    /**
     * @param $email
     * @return Customer
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function findByEmail($email)
    {
        $data = $this->search('email', $email);

        if (count($data)) {
            return $this->findById($data->first());
        }else{
            throw new UnexpectedException('No customers found');
        }
    }

    /**
     * @param $email
     * @return Customer
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function findByCustomerNumber($customerNumber)
    {
        echo 'find by customer id';
        $data = $this->search('customerNo', $customerNumber);

        if (count($data)) {
            return $this->findById($data->first());
        }else{
            throw new UnexpectedException('No customers found');
        }
    }

    /**
     * @param Customer $customer
     * @return Customer
     *
     * PUT  https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     * POST https://hostname:port/dw/data/v18_1/customer_lists/{list_id}/customers/{customer_no}/addresses
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     * @throws \Arkade\Demandware\Exceptions\TokenNotFoundException
     */
    public function create(Customer $customer)
    {
        $jwt = $this->client->getCustomerAuth(
            $this->useShop("/customers/auth?client_id={$this->getClientId()}")
        );

        if (empty($jwt)) {
            throw new TokenNotFoundException('Customer JWT token could not be found');
        }

        $customerResponse = (new CustomerParser)->parse(
            $this->client->post(
                $this->useShop('/customers'),
                [
                    'body'    => (new CustomerSerializer)->serialize($customer, true),
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'Authorization' => $jwt
                    ],
                ]
            )
        );

        $customerNo = $customerResponse->getCustomerNo();
        if ($customer->getPrimaryAddress()) {
            $this->client->post(
                $this->useData("customer_lists/{$this->getSiteName()}/customers/{$customerNo}/addresses"),
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => (new AddressSerializer)->serialize($customer->getPrimaryAddress()),
                ]
            );
        }

        return $this->findById($customerNo);
    }


    /**
     * Create customer via customer_list endpoint
     * @param Customer $customer
     * @return Customer
     * @throws TokenNotFoundException
     * @throws UnexpectedException
     */
    public function customerListCreate(Customer $customer) {

        $customerResponse = (new CustomerParser)->parse(
            $this->client->post(
                $this->useData("customer_lists/{$this->getSiteName()}/customers"),
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => (new CustomerListCustomerSerializer)->serialize($customer, true)
                ]
            ));

        $customerNo = $customerResponse->getCustomerNo();
        if ($customer->getPrimaryAddress()) {
            $this->client->post(
                $this->useData("customer_lists/{$this->getSiteName()}/customers/{$customerNo}/addresses"),
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => (new AddressSerializer)->serialize($customer->getPrimaryAddress()),
                ]
            );
        }

        return $this->findById($customerNo);
    }

    /**
     * @param Customer $customer
     * @return \Arkade\Demandware\Entities\Customer
     *
     * PATCH https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function update(Customer $customer)
    {
        $customerResponse = (new CustomerParser)->parse(
            $this->client->patch(
                $this->useData("customer_lists/{$this->getSiteName()}/customers/{$customer->getCustomerNo()}"),
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => (new CustomerSerializer)->serialize($customer),
                ]
            )
        );

        $customerNo = $customerResponse->getCustomerNo();
        if ($customer->getPrimaryAddress()) {
            if($customer->getPrimaryAddress()->getAddressId()) {
                $this->client->patch(
                    $this->useData("customer_lists/{$this->getSiteName()}/customers/{$customerNo}/addresses/{$customer->getPrimaryAddress()->getAddressId()}"),
                    [
                        'headers' => ['Content-Type' => 'application/json'],
                        'body' => (new AddressSerializer)->serialize($customer->getPrimaryAddress()),
                    ]
                );
            }
        }

        return $this->findById($customerNo);
    }

    /**
     * @param string $fieldName
     * @param string $searchPhrase
     * @return Collection
     *
     * POST https://hostname:port/dw/data/v17_8/customer_lists/{customer_list_id}/customer_search
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function search(string $fieldName, string $searchPhrase)
    {
        $query = [
            'query' =>
                [
                    'text_query' => [
                        'fields'        => [$fieldName],
                        'search_phrase' => $searchPhrase
                    ]
                ]
        ];

        $data = $this->client->post(
            $this->useData("customer_lists/{$this->getSiteName()}/customer_search"),
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => json_encode($query),
            ]
        );

        $collection = new Collection;

        if ($data->count == 0) {
            return $collection;
        }

        foreach ($data->hits as $item) {
            $collection->push($item->data->customer_no);
        }

        return $collection;
    }
}
