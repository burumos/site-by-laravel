<?php

namespace App\Http\Controllers;

// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidatesRequests;
// use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use App\User;

class TopController extends Controller {

    public function index() {
        return view('top', []);
    }
}
