<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user        = $request->user();
        $application = $user->organizerApplication;

        $recentOrders = Order::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->limit(3)
            ->get();

        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('total_price');

        $totalTickets = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('quantity');

        return view('dashboard', compact('user', 'application', 'recentOrders', 'totalSpent', 'totalTickets'));
    }
}
