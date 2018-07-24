<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Transfert\Arret\Repo\ArretInterface;
use App\Http\Resources\ArretCollection as ArretCollection;

class ArretController extends Controller
{
    protected $arret;

    public function __construct(ArretInterface $arret)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->arret = $arret;
    }

    public function index()
    {
        $jurisprudence = new \App\Droit\Api\Jurisprudence();

        $sites = $jurisprudence->getModel('Site')->setConnection('testing_transfert');
        $site = $sites->first();

        $arrets  = $jurisprudence->setConnection('testing_transfert')->setSite($site->id)->arrets();

        return new ArretCollection($arrets);
    }
}


