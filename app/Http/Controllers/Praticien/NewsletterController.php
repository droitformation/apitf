<?php

namespace App\Http\Controllers\Praticien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class NewsletterController extends Controller
{
    protected $decision;
    protected $worker;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
    }

    public function index()
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


