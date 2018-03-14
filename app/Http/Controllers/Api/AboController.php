<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\User\Repo\UserInterface;
use App\Droit\Abo\Worker\AboWorkerInterface;

class AboController extends Controller
{
    protected $user;
    protected $abo;

    public function __construct(UserInterface $user, AboWorkerInterface $abo)
    {
        $this->user = $user;
        $this->abo = $abo;
    }

    public function make(Request $request)
    {
        $this->abo->make($request->all());

        return response()->json($request->all());
    }

    public function remove(Request $request)
    {
        $this->abo->remove($request->all());

        return response()->json($request->all());
    }

    public function cadence(Request $request)
    {
        $this->user->update(['id' => $request->input('user_id'), 'cadence' => $request->input('cadence')]);

        return response()->json($request->all());
    }
}


