<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendEmailAlert;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendAlertes extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:alert {cadence} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alertes for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cadence = $this->argument('cadence');
        $date    = $this->argument('date');
        $date    = $date ? $date : date('Y-m-d');

        if($cadence == 'weekly'){
            $date = weekRange($date)->toArray();
        }

        $this->dispatch((new SendEmailAlert($date, $cadence)));
    }
}
