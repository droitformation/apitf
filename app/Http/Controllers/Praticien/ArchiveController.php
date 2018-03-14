<?php

namespace App\Http\Controllers\Praticien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;
use App\Droit\Categorie\Repo\CategorieInterface;

class ArchiveController extends Controller
{
    protected $decision;
    protected $worker;
    protected $categorie;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker, CategorieInterface $categorie)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
        $this->categorie = $categorie;
    }

    public function index()
    {
        $liste = $this->worker->getMissingDates();
        $exist = $this->worker->getExistDatesArrets();
        $total = $this->decision->countByYear();

        return view('praticien.index')->with(['liste' => $liste, 'exist' => $exist, 'total' => $total]);
    }

    public function archives($year, $date ,$id = null)
    {
        $decisions = $this->decision->getDateArchive($date,$year);
        $arret  = $id ? $this->decision->findArchive($id,$year) : null;

        return view('praticien.archives')->with(['decisions' => $decisions, 'arret' => $arret, 'date' => $date, 'year' => $year]);
    }

    public function testing(Request $request)
    {
        $tables     = array_map('reset', \DB::connection('mysql')->select('SHOW TABLES'));
        $categories = $this->categorie->getAll();

        $results = $request->input('terms',null) || $request->input('categorie_id',null) || $request->input('period',null) ?
            $this->decision->searchArchives([
                'period' => array_filter($request->input('period')),
                'categorie_id' => $request->input('categorie_id',null),
                'published' => $request->input('published',null),
                'terms' => $request->input('terms')
            ]) :
            collect([]);

        return view('praticien.current')->with(['tables' => $tables, 'results' => $results,'categories' => $categories, 'search' => $request->except('_token')]);
    }

    public function archive()
    {
        $tables = \DB::connection('sqlite')->select("select name from sqlite_master WHERE type='table'");
        $total  = $this->decision->setConnection('sqlite')->archiveCountByYear();

        return view('praticien.archive')->with(['tables' => $tables, 'total' => $total]);
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


