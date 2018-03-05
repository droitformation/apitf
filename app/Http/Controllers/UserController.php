<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Categorie\Repo\CategorieInterface;
use App\Droit\Bger\Worker\AlertInterface;

class UserController extends Controller
{
    protected $decision;
    protected $worker;
    protected $user;
    protected $categorie;
    protected $alert;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker, UserInterface $user, CategorieInterface $categorie, AlertInterface $alert)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
        $this->user = $user;
        $this->categorie = $categorie;
        $this->alert = $alert;
    }

    public function index(Request $request)
    {
        $users      =  $this->user->getAll();
        $categories = $this->categorie->getAll();

        if($request->input('user_id')){
            $user  =  $this->user->find($request->input('user_id'));

            $this->alert->setCadence('week')->setDate($request->input('date'));
            $data = $this->alert->getUserAbos($user);

            $alert = (new \App\Mail\AlerteDecision($user, $request->input('date'), $data))->render();
        }

        return view('users')->with([
            'users' => $users,
            'user_id' => $request->input('user_id'),
            'categories' => $categories->pluck('name','id'),
            'alert' => isset($alert) ? $alert : null
        ]);
    }

    public function show(Request $request)
    {
        $user = $this->user->find($request->input('id'));
        $data = [
            'id' => $user->id,
            'cadence' => $user->cadence,
            'abonnements' => $user->abos_api
        ];

        return response()->json($data);
    }

}


