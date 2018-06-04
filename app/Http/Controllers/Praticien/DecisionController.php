<?php

namespace App\Http\Controllers\Praticien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class DecisionController extends Controller
{
    protected $decision;
    protected $worker;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
    }

    public function index($date, $id = null)
    {
        $decisions = $this->decision->setConnection('mysql')->getDate($date);
        $arret  = $id ? $this->decision->setConnection('mysql')->find($id) : null;

        return view('praticien.decisions')->with(['decisions' => $decisions, 'arret' => $arret, 'date' => $date]);
    }

    public function update(Request $request)
    {
        $grab     = new \App\Droit\Bger\Utility\Decision();
        $decision = $grab->setDecision($request->except('year','_token'))->getArret();

        $this->decision->update(['id' => $request->input('id')] + $decision);

        return redirect()->back()->with(['message' => 'Arrêt mis à jour: '.$decision['numero']]);
    }
}
