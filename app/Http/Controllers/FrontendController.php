<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class FrontendController extends Controller
{
    protected $decision;
    protected $worker;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
    }

    public function index()
    {
        $liste = $this->worker->getMissingDates();
        $exist = $this->worker->getExistDatesArrets();
        $total = $this->decision->countByYear();

        return view('welcome')->with(['liste' => $liste, 'exist' => $exist, 'total' => $total]);
    }

    public function current()
    {
        $tables = array_map('reset', \DB::connection('mysql')->select('SHOW TABLES'));

        return view('current')->with(['tables' => $tables]);
    }

    public function archive()
    {
        $tables = \DB::connection('sqlite')->select("select name from sqlite_master WHERE type='table'");
        $total  = $this->decision->setConnection('sqlite')->archiveCountByYear();

        return view('archive')->with(['tables' => $tables, 'total' => $total]);
    }

    public function decision($id)
    {

    }
}
