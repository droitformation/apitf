<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Transfert\Arret\Repo\ArretInterface;
use App\Http\Resources\ArretCollection as ArretCollection;
use App\Http\Resources\CategorieCollection as CategorieCollection;
use App\Http\Resources\AnneeCollection as AnneeCollection;
use App\Http\Resources\AuthorCollection as AuthorCollection;

class ContentController extends Controller
{
    protected $api;
    protected $arret;

    public function __construct(ArretInterface $arret)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->api = new \App\Droit\Api\Jurisprudence();
        $this->arret = $arret;
    }

    /*
     *  /api/arrets?params[site_id]=1&params[years][]=2017&params[categories][]=32
     *  $params['site_id'] = 1
     *  $params['years'] = [2018,2017]
     *  $params['categories'] = [32,34]
     * */
    public function arrets(Request $request)
    {
        $this->api->setConnection('testing_transfert')->setSite($request->input('params')['site_id']);

        $arrets = $this->api->arrets($request->input('params'));

        return new ArretCollection($arrets);
    }

    public function categories(Request $request)
    {
        $this->api->setConnection('testing_transfert')->setSite($request->input('params')['site_id']);

        $categories = $this->api->categories();

        return new CategorieCollection($categories);
    }

    public function annees(Request $request)
    {
        $this->api->setConnection('testing_transfert')->setSite($request->input('params')['site_id']);

        $annees = $this->api->annees();

        return response()->json($annees);
    }

    public function authors(Request $request)
    {
        $this->api->setConnection('testing_transfert')->setSite($request->input('params')['site_id']);

        $authors = $this->api->authors();

        return new AuthorCollection($authors);
    }
}
