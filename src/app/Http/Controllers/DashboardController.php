<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $application = $user->organizerApplication;

        return view('dashboard', compact('user', 'application'));
    }
}
