<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class DecisionController extends Controller
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

    public function index($date, $id = null)
    {
        $decisions = $this->decision->setConnection('mysql')->getDate($date);
        $arret  = $id ? $this->decision->setConnection('mysql')->find($id) : null;

        return view('decisions')->with(['decisions' => $decisions, 'arret' => $arret, 'date' => $date]);
    }

    public function show($id)
    {

    }
}
