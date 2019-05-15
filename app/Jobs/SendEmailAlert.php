<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $publication_at;
    protected $cadence;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($publication_at, $cadence)
    {
        $this->publication_at = $publication_at;
        $this->cadence = $cadence;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $alert = \App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence($this->cadence)->setDate($this->publication_at);

        $users = $alert->getUsers();

        // No alerts today
        if($users->isEmpty()){
            \Mail::to('cindy.leschaud@gmail.com')->send(new \App\Mail\SuccessNotification('Aucune alertes'));
        }

        \Mail::to('cindy.leschaud@gmail.com')
            ->send(new \App\Mail\SuccessNotification('Envoi des alertes commencé'));

        $i = 1;
        // Loop all users and send the alerts
        foreach ($users as $user) {
            $i++;
            $when = now()->addSeconds($i);
            //\Mail::to($user['user']->email)->send(new \App\Mail\AlerteDecision($user['user'], $this->publication_at, $user['abos']));
            \Mail::to('cindy.leschaud@gmail.com')->later($when, new \App\Mail\AlerteDecision($user['user'], $this->publication_at, $user['abos'])); // Testing

            $alert->sent($user['user']);// Mark as sent
        }

    }
}
