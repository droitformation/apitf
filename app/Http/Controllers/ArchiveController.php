<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class ArchiveController extends Controller
{
    protected $decision;
    protected $worker;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
    }

    public function index($year, $date ,$id = null)
    {
        $decisions = $this->decision->getDateArchive($date,$year);
        $arret  = $id ? $this->decision->findArchive($id,$year) : null;

        return view('archives')->with(['decisions' => $decisions, 'arret' => $arret, 'date' => $date, 'year' => $year]);
    }

    public function transfert(Request $request)
    {
        $year = $request->input('year');
        $table = new \App\Droit\Bger\Utility\Table();

        // Make archives
        $table->mainTable = 'decisions';
        $table->setYear($year)->canTransfert()->create()->transfertArchives();
        $table->deleteLastYear();

        return redirect()->back()->with(['message' => 'Année Archivé: '.$year]);

    }

}


