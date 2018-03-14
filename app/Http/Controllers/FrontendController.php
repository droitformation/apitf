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
        $results = [];

        if($request->input('verify',null)){
            $ipverify = new \App\Droit\Uptime\IP();
            $ips = config('ips');
            foreach($ips as $ip){
                $results[] = $ipverify->verify($ip);
            }
        }

        return view('welcome')->with(['results' => $results]);
    }

}
