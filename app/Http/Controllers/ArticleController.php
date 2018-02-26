<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Droit\Decision\Repo\DecisionInterface;
use Illuminate\Support\Facades\App;
use \ForceUTF8\Encoding;

class ArticleController extends Controller
{
    protected $decision;

    public function __construct(DecisionInterface $decision)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
    }

    public function index(Request $request)
    {
        //$results = $this->decision->setConnection('sqlite')->getAll();
   /*     $results = $this->decision->getAll();

        $results->map(function ($decision, $key) {
            $this->decision->setConnection('sqlite')->create($decision->toArray());
        });*/

        $term = 'Marazzi et Herrmann';
        $start_at = \Carbon\Carbon::parse('2017-01-02')->toDateTimeString();
        $end_at   = \Carbon\Carbon::parse('2017-01-03')->toDateTimeString();

        $results = $this->decision->setConnection('sqlite')->searchTableLite('decisions',['terms' => [$term], 'period' => [$start_at,$end_at]]);

        echo '<pre>';
        print_r($results);
        echo '</pre>';exit();
    }
}
