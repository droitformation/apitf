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

    public function index($date = null)
    {
        $date  = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();
        $today = $date->formatLocalized("%A %d %B %Y");

        $dates = weekRange($date->toDateString());
        $start = $dates->first();
        $end   = $dates->last();
        $week  = \Carbon\Carbon::parse($start)->formatLocalized("%d %B %Y").' au '.\Carbon\Carbon::parse($end)->formatLocalized("%d %B %Y");

        $more = '/';
        $unsuscribe = '/';

        $arrets = $this->decision->getWeekPublished($dates->toArray());

        return view('emails.newsletter')->with(['arrets' => $arrets, 'date' => $today, 'week' => $week, 'more' => $more, 'unsuscribe' => $unsuscribe]);
    }

    public function letter(Request $request)
    {
        $html = null;

        if($request->input('date', null))
        {
            $start = \Carbon\Carbon::createFromFormat('Y-m-d',$request->input('date'))->startOfWeek();
            $end   = \Carbon\Carbon::createFromFormat('Y-m-d',$request->input('date'))->endOfWeek();

            $date = \Carbon\Carbon::now()->formatLocalized("%A %d %B %Y");
            $week = $start->formatLocalized("%d %B %Y").' au '.$end->formatLocalized("%d %B %Y");
            $more = '/';
            $unsuscribe = '/';

            $arrets = $this->decision->getWeekPublished(weekRange($start,$end));

            $html = view('emails.newsletter')->with(['arrets' => $arrets, 'date' => $date, 'week' => $week, 'more' => $more, 'unsuscribe' => $unsuscribe])->render();
        }

        return view('praticien.letter')->with(['html' => $html]);
    }

    public function send()
    {
        $mailjet = new \App\Droit\Newsletter\NewsletterWorker();

        return $mailjet->send();
    }
}


