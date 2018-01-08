<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware\Modules\AbstractModule;

Class Customers Extends AbstractModule
{

    /**
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getFromId($id)
    {
        return $this->client->get("customer_lists/{$this->getSiteName()}/customers/{$id}");
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        return $this->client->get("customer_lists/{$this->getSiteName()}");
    }

    /**
     * @param array $customerData
     * @return \Psr\Http\Message\ResponseInterface
     *
     * PUT https://hostname:port/dw/data/v17_8/customer_lists/{list_id}/customers/{customer_no}
     */
    public function create(array $customerData)
    {
        return $this->client->post("customer_lists/{$this->getSiteName()}/customers", ['json' => $customerData]);
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
        return $this->client->patch("customer_lists/{$this->getSiteName()}/customers/{$customerNo}", ['json' => $customerData]);
    }

    /**
     * @param array $searchQuery
     * @return \Psr\Http\Message\ResponseInterface
     *
     * POST https://hostname:port/dw/data/v17_8/customer_lists/{customer_list_id}/customer_search
     */
    public function search(array $searchQuery)
    {
        return $this->client->post("customer_lists/{$this->getSiteName()}/customer_search", [
            'json' => ['query' => $searchQuery]
        ]);
    }


}