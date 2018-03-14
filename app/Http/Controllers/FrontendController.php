<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FrontendController extends Controller
{

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function index()
    {
        return view('welcome');
    }
}
