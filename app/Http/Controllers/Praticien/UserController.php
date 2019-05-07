<?php

namespace App\Http\Controllers\Praticien;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\User\Worker\UserWorkerInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Bger\Worker\AlertInterface;

class UserController extends Controller
{
    protected $worker;
    protected $user;
    protected $categorie;
    protected $alert;

    public function __construct(UserWorkerInterface $worker, UserInterface $user, CategorieInterface $categorie, AlertInterface $alert)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->worker    = $worker;
        $this->user      = $user;
        $this->categorie = $categorie;
        $this->alert     = $alert;
    }

    public function index(Request $request)
    {
        $users      = $this->user->getAll();
        $categories = $this->categorie->getAll();
        $date       = $request->input('date');

        if($request->input('user_id')){
            $user = $this->user->find($request->input('user_id'));

            if($request->input('cadence') == 'weekly'){
                $date = weekRange($request->input('date'))->toArray();
            }

            $this->alert->setCadence($request->input('cadence'))->setDate($date);
            $data = $this->alert->getUserAbos($user);

            $alert = (new \App\Mail\AlerteDecision($user, $date, $data))->render();
        }

        return view('praticien.users')->with([
            'users' => $users,
            'user_id' => $request->input('user_id'),
            'categories' => $categories->pluck('name','id'),
            'alert' => isset($alert) ? $alert : null
        ]);
    }
}


