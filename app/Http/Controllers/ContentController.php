<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContentController extends Controller
{
    protected $api;

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->api = new \App\Droit\Api\Jurisprudence();
    }

    public function index()
    {
        $model = $this->api->getModel('Site')->setConnection('testing_transfert');
        $model = $model->first();

        $this->api->setConnection('testing_transfert')->setSite($model->id);

        $arrets     = $this->api->arrets(['years' => [2018,2017]]);
        $analyses   = $this->api->analyses(['years' => [2018]]);
        $categories = $this->api->categories();
        $annees     = $this->api->annees();

        return response()->json($annees);
    }
}
