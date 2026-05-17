<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search   = $request->query('search');

        $events = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%"))
            ->orderBy('event_date')
            ->paginate(12);

        $categories = Event::categories();

        return view('events.index', compact('events', 'categories', 'category', 'search'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $userOrder = null;
        if (auth()->check()) {
            $userOrder = $event->orders()->where('user_id', auth()->id())->latest()->first();
        }

        return view('events.show', compact('event', 'userOrder'));
    }
}
