<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('site.home');
    }

    public function stats(?string $date = null)
    {
        return view('site.stats', ['date' => $date]);
    }
}
