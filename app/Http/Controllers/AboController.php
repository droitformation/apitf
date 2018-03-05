<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\User\Repo\UserInterface;
use App\Droit\Abo\Repo\AboInterface;

class AboController extends Controller
{
    protected $user;
    protected $abo;

    public function __construct(UserInterface $user, AboInterface $abo)
    {
        $this->user = $user;
        $this->abo = $abo;
    }

    public function abo(Request $request)
    {
        return response()->json($request->all());
    }

}


