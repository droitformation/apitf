<?php namespace App\Droit\Uptime;

//use Goutte\Client;
//use GuzzleHttp\Client;

class IP
{
    protected $client;
    protected $base_url;

    public function __construct()
    {
        $this->base_url = 'https://neutrinoapi.com/ip-blocklist';

        //  Hackery to allow HTTPS
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
    }

    public function getData($params){

        $response = $this->client->request('POST',  $this->base_url, [
            'query' => $params
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function verify($ip)
    {
        $params = array(
            "user-id" => env('NEUTRINO_USER_ID'),
            "api-key" => env('NEUTRINO_API_KEY'),
            "ip" => $ip
        );

        return $this->getData($params);
    }
}
