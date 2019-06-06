<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    public function__construct()
{
    $this->middleware('cas.auth');
}
}
