<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke() : mixed
    {
        dump(auth()->user());
        return view('index');
    }
}
