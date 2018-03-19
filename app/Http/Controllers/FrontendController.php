<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FrontendController extends Controller
{

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index(Request $request)
    {
        $ipverify = new \App\Droit\Uptime\IP();

        $results = [];

        $logs = $ipverify->logs();

        if($request->input('verify',null)){
            $mailgun = $ipverify->mailgun();
            $ips     = array_merge(config('ips'),['mailgun_main' => $mailgun]);

            foreach($ips as $name => $ip){
                $results[$name] = $ipverify->verify($ip);
            }
        }

        return view('welcome')->with(['results' => $results, 'logs' => $logs]);
    }

}
