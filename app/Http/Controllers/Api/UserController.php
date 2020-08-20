<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\User\Worker\UserWorkerInterface;
use App\Droit\User\Repo\UserInterface;

class UserController extends Controller
{
    protected $worker;
    protected $user;

    public function __construct(UserWorkerInterface $worker, UserInterface $user)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->worker    = $worker;
        $this->user      = $user;
    }

    public function show(Request $request)
    {
        $data = $request->input('data',null);

        if($data){
            $data['active_until'] = $request->input('active_until');
            $data['name']         = $request->input('name');
            $data['cadence']      = $request->input('cadence');
        }

        $user = $this->worker->find($request->input('id'), $data);

        $data = [
            'id'          => $user->id,
            'name'        => $user->name,
            'cadence'     => $user->cadence,
            'abonnements' => $user->abos_api
        ];

        return response()->json($data);
    }
}


