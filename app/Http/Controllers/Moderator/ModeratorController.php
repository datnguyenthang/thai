<?php

namespace App\Http\Controllers\Moderator;

use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    { 
        return view('livewire.backend.moderator.dashboard');
    }
}
