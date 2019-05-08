<?php namespace App\Droit\Uptime;

//use Goutte\Client;
//use GuzzleHttp\Client;

class IP
{
    protected $client;
    protected $base_url;
    protected $uptime_robot;

    public function __construct()
    {
        $this->base_url = 'https://cymon.io:443/api/nexus/v1/';
        $this->uptime_robot = 'https://api.uptimerobot.com/v2/getMonitors';

        //  Hackery to allow HTTPS
        $this->client = new \GuzzleHttp\Client(['verify' => false]);

    }

    public function verify($ip){

        try{
            $response = $this->client->request('GET',  $this->base_url.'ip/'.$ip, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer '.env('CYMON_KEY')
                ]
            ]);

            return json_decode($response->getBody(), true);
        }
        catch (\Exception $exception){
            return ['ip' => $ip, 'status' => 'ok'];
        }
    }

    public function mailgun()
    {
        $mailgun = new \Mailgun\Mailgun(config('mailgun.api_key'));

        $response = $mailgun->get('domains/'.config('mailgun.domain').'/ips');

        if($response->http_response_code == 200){
            return $response->http_response_body->items[0];
        }

        return null;
    }

    public function uptimerobot()
    {
        try {
            $response = $this->client->request('POST',  $this->uptime_robot, [
                'form_params' => [
                    'api_key' => env('UPTIMEROBOT'),
                    'format'  => 'json',
                    'logs'    => '1',
                ],
                'headers' => [
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            return json_decode($response->getBody(), true);

        } catch (HttpException $ex) {
            echo $ex;
        }
    }

    public function logs()
    {
        $results = $this->uptimerobot();

        if(!isset($results['monitors'])){
            return collect([]);
        }

        return collect($results['monitors'])->mapWithKeys(function ($item) {
            return [$item['friendly_name'] => $item['logs']];
        });
    }
}
