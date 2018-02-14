<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Entities\Customer;
use Arkade\Demandware\Exceptions\TokenNotFoundException;
use Arkade\Demandware\Parsers\CustomerParser;
use Arkade\Demandware\Serializers\AddressSerializer;
use Arkade\Demandware\Serializers\CustomerSerializer;
use Illuminate\Support\Collection;

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
     * @return Customer|null
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     */
    public function findByEmail($email)
    {
        $data = $this->search('email', $email);

        if (count($data)) {
            return $this->findById($data->first());
        }

        return null;
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
                    'debug'   => env('HTTP_DEBUG', false),
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
                    'debug'   => env('HTTP_DEBUG', false)
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
        return (new CustomerParser)->parse(
            $this->client->patch(
                "customer_lists/{$this->getSiteName()}/customers/{$customer->getCustomerNo()}",
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body'    => (new CustomerSerializer)->serialize($customer),
                    'debug'   => env('HTTP_DEBUG', false)
                ]
            )
        );
    }

    /**
     * @param string $fieldName
     * @param string $searchPhrase
     * @return Collection
     *
     * POST https://hostname:port/dw/data/v17_8/customer_lists/{customer_list_id}/customer_search
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

        $data = $this->client->post("customer_lists/{$this->getSiteName()}/customer_search",
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => json_encode($query),
                'debug'   => env('HTTP_DEBUG', false)
            ]);

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