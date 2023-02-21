<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function Home() {
        $data = app('App\Http\Controllers\Website\MainController')->home()->getData();

        return view('web.home', compact('data'));
    }
}
