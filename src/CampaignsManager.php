<?php

namespace Liliom\Zoho\Campaigns;


class CampaignsManager
{

    /**
     * @var CampaignsClient
     */
    public $client;

    /**
     * CampaignsManager constructor.
     */
    public function __construct()
    {
        $this->with();
    }

    /**
     * Create new Campaigns client instance.
     *
     *
     * @return $this
     * @throws \Exception
     */
    public function with()
    {
        $authToken = config('services.zoho.campaigns.auth_token');
        if (is_null($authToken)) {
            throw new \Exception('Invalid Zoho Campaign Auth Token, please make sure to set the value of APP ID within your `config/services.php`');
        }
        $this->client = new CampaignsClient($authToken);

        return $this;
    }


    /**
     * Dynamically call methods on the Campaigns client.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ( ! method_exists($this->client, $method)) {
            abort(500, "Method $method does not exist");
        }

        return call_user_func_array([$this->client, $method], $parameters);
    }

}
