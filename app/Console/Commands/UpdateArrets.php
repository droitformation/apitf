<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateArrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:arret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update arrets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $worker = \App::make('App\Droit\Decision\Worker\DecisionWorkerInterface');
        $worker->setMissingDates()->update();

        \Mail::to('cindy.leschaud@gmail.com')->send(new \App\Mail\SuccessNotification('Mise à jour des décisions commencé'));
    }
}
