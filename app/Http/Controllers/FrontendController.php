<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;
use App\Droit\Categorie\Repo\CategorieInterface;

class FrontendController extends Controller
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

        return view('welcome')->with(['liste' => $liste, 'exist' => $exist, 'total' => $total]);
    }

    public function current(Request $request)
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

        return view('current')->with(['tables' => $tables, 'results' => $results,'categories' => $categories, 'search' => $request->except('_token')]);
    }

    public function archive()
    {
        $tables = \DB::connection('sqlite')->select("select name from sqlite_master WHERE type='table'");
        $total  = $this->decision->setConnection('sqlite')->archiveCountByYear();

        return view('archive')->with(['tables' => $tables, 'total' => $total]);
    }

    public function tfnewsletter()
    {
        $start = \Carbon\Carbon::now()->startOfWeek();
        $end   = \Carbon\Carbon::now()->endOfWeek();

        $date = \Carbon\Carbon::now()->formatLocalized("%A %d %B %Y");
        $week = $start->formatLocalized("%d %B %Y").' au '.$end->formatLocalized("%d %B %Y");
        $more = '/';
        $unsuscribe = '/';

        $arrets = $this->decision->getWeekPublished(generateDateRange($start,$end));

        return view('emails.newsletter')->with(['arrets' => $arrets, 'date' => $date, 'week' => $week, 'more' => $more, 'unsuscribe' => $unsuscribe]);
    }
}
