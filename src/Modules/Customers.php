<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Parsers\CustomerParser;

Class Customers Extends AbstractModule
{

    /**
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
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
     * @return \Psr\Http\Message\ResponseInterface
     *
     * PUT https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     */
    public function create(array $customerData)
    {
        return (new CustomerParser)->parse(
            $this->client->post(
                "customer_lists/{$this->getSiteName()}/customers",
                ['json' => $customerData]
            )
        );
    }

    /**
     * @param int $customerNo
     * @param array $customerData
     * @return \Psr\Http\Message\ResponseInterface
     *
     * PATCH https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     */
    public function update(string $customerNo, array $customerData)
    {
        return (new CustomerParser)->parse(
            $this->client->patch(
                "customer_lists/{$this->getSiteName()}/customers/{$customerNo}",
                ['json' => $customerData]
            )
        );
    }

    /**
     * @param array $searchQuery
     * @return \Psr\Http\Message\ResponseInterface
     *
     * POST https://hostname:port/dw/data/v17_8/customer_lists/{customer_list_id}/customer_search
     */
    public function search(array $searchQuery)
    {
        $data = $this->client->post("customer_lists/{$this->getSiteName()}/customer_search", [
            'json' => ['query' => $searchQuery]
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