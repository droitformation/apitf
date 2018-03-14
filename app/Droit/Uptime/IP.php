<?php namespace App\Droit\Uptime;

//use Goutte\Client;
//use GuzzleHttp\Client;

class IP
{
    protected $client;
    protected $base_url;

    public function __construct()
    {
        $this->base_url = 'https://cymon.io:443/api/nexus/v1/';

        //  Hackery to allow HTTPS
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
    }

    public function verify($ip){

        $response = $this->client->request('GET',  $this->base_url.'ip/'.$ip, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.env('CYMON_KEY')
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
