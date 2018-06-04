<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;

class ManualUpdateDateDecisions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dates;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $dates)
    {
        $this->dates = $dates;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $worker = \App::make('App\Droit\Decision\Worker\DecisionWorkerInterface');
        $worker->setMissingDates($this->dates)->update();

        \Mail::to('cindy.leschaud@gmail.com')->queue(new \App\Mail\SuccessNotification('Mise à jour des décisions commencé'));
    }
}
