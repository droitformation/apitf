<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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

    public function alerte()
    {
        $repo = App::make('App\Droit\User\Repo\UserInterface');
        $user = $repo->find(1);

        $today  =  $publication_at = \Carbon\Carbon::today()->startOfDay();
        $tomorrow  =  $publication_at = \Carbon\Carbon::today()->startOfDay()->toDateString();
        /*    */
        $monday = $today->startOfWeek();
        $friday = $today->startOfWeek()->parse('this friday');

        $dates = generateDateRange($monday, $friday);

        //$make->multipleAbos($user,$data1);

        $alert  = App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence('daily')->setDate($tomorrow);

        $users = $alert->getUsers();

        /*
                echo '<pre>';
                print_r($users);
                echo '</pre>';exit;*/

        foreach ($users as $users) {
            echo view('emails.alert')->with(['user' => $users['user'], 'date' => $tomorrow, 'arrets' => $users['abos']]);
        }
    }
}
