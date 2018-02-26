<?php namespace App\Droit\Bger\Worker;

class SendAlert
{

    public function handle()
    {
        $alert = \App::make('App\Droit\Bger\Worker\AlertInterface');
        $alert->setCadence($this->cadence)->setDate($this->publication_at);
        $users = $alert->getUsers();

        $date  = formatDateOrRange($this->publication_at);

        if (!$users->isEmpty()){
            foreach ($users as $user) {

                \Mail::to($user['user']->email)->queue(new \App\Mail\AlerteDecision($user['user'], $date, $user['abos']));
                // Mark as sent
                $alert->sent($user['user']);
            }

            \Mail::to('cindy.leschaud@gmail.com')->queue(new \App\Mail\SuccessNotification('Envoi des alertes commencé'));
        }
        else{
            \Mail::to('cindy.leschaud@gmail.com')->queue(new \App\Mail\SuccessNotification('Aucune alertes'));
        }
    }

}