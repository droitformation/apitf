<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;

class DateController extends Controller
{
    protected $decision;
    protected $worker;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
    }

    public function update(Request $request)
    {
        if($request->input('date',null)){
            $dates = $request->input('date');
        }

        if($request->input('range',null)){
            $dates = generateDateRange(\Carbon\Carbon::parse($request->input('range')[0]), \Carbon\Carbon::parse($request->input('range')[1]));
        }

        $this->worker->setMissingDates(collect($dates))->update();

        return redirect()->back()->with(['message' => 'update']);
    }

    public function delete(Request $request)
    {
        $this->decision->deleteDate([$request->input('date')]);

        return redirect()->back()->with(['message' => 'deleted']);
    }
}


