<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //public
    public function managerIndex()
    {
        return view('master');
    }

    public function cashierIndex()
    {
        return view('master');
    }
}
