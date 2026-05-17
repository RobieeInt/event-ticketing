<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerApplication;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending'  => OrganizerApplication::where('status', 'pending')->count(),
            'approved' => OrganizerApplication::where('status', 'approved')->count(),
            'rejected' => OrganizerApplication::where('status', 'rejected')->count(),
            'total'    => OrganizerApplication::count(),
            'users'    => User::where('role', 'user')->count(),
            'organizers' => User::where('role', 'organizer')->count(),
        ];

        $recentApplications = OrganizerApplication::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentApplications'));
    }
}
