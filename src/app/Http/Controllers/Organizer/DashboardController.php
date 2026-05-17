<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total'     => Event::where('user_id', $user->id)->count(),
            'published' => Event::where('user_id', $user->id)->where('status', 'published')->count(),
            'draft'     => Event::where('user_id', $user->id)->where('status', 'draft')->count(),
            'cancelled' => Event::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $recentEvents = Event::where('user_id', $user->id)->latest()->limit(5)->get();

        return view('organizer.dashboard', compact('stats', 'recentEvents'));
    }
}
