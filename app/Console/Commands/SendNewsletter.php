<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter {test?} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly newsletter of arrets for publicationw';

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
        $worker = \App::make('App\Droit\Newsletter\NewsletterWorker');
        $url    = 'praticien/newsletter';

        $date = $this->argument('date');

        if(isset($date)){
            $url = $url.'/'.$date;
        }

        $test = $this->argument('test');

        if(isset($test)){
            $worker->setUrl($url)->send_test();
        }
        else{
            $worker->setUrl($url)->send();
        }
    }
}
