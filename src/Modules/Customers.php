<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Entities\Customer;
use Arkade\Demandware\Parsers\CustomerParser;
use Arkade\Demandware\Serializers\CustomerSerializer;
use Illuminate\Support\Collection;

Class Customers Extends AbstractModule
{

    /**
     * @param $id
     * @return Customer
     */
    public function getFromId($id)
    {
        return (new CustomerParser)->parse(
            $this->client->get(
                "customer_lists/{$this->getSiteName()}/customers/{$id}"
            )
        );
    }

    /**
     * @param array $customerData
     * @return Customer
     *
     * PUT https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     */
    public function create(Customer $customer)
    {
        return (new CustomerParser)->parse(
            $this->client->post(
                "customer_lists/{$this->getSiteName()}/customers",
                ['json' => (new CustomerSerializer)->serialize($customer)]
            )
        );
    }

    /**
     * @param int $customerNo
     * @param array $customerData
     * @return Customer
     *
     * PATCH https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     */
    public function update(Customer $customer)
    {
        return (new CustomerParser)->parse(
            $this->client->patch(
                "customer_lists/{$this->getSiteName()}/customers/{$customer->getCustomerNo()}",
                ['json' => (new CustomerSerializer)->serialize($customer)]
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
        $data = $this->client->post("customer_lists/{$this->getSiteName()}/customer_search", [
            'json' => ['query' =>
                [
                    'text_query' => [
                        'fields' => [$fieldName],
                        'search_phrase' => $searchPhrase
                    ]
                ]
            ]
        ]);

        $collection = new Collection;

        if($data->count == 0) return $collection;

        foreach($data->hits as $item){
            $collection->push(
                (new CustomerParser)->parse($item)
            );
        }

        return $collection;
    }


}