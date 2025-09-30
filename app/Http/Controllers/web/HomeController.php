<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Models\Logs;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');  // Ensure you have a 'home.blade.php' view
    }

    public function logs()
    {
        $logs = Logs::orderBy('created_at', 'desc')->get();
        return view('logs', compact('logs'));
    }


}
