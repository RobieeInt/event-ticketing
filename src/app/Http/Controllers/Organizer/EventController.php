<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $events = Event::where('user_id', $request->user()->id)
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events', 'status'));
    }

    public function create()
    {
        $categories = Event::categories();

        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'banner_image'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
            'category'        => ['required', 'string', 'in:' . implode(',', array_keys(Event::categories()))],
            'event_date'      => ['required', 'date', 'after:now'],
            'location'        => ['required', 'string', 'max:500'],
            'ticket_price'    => ['required', 'numeric', 'min:0'],
            'ticket_capacity' => ['required', 'integer', 'min:1'],
            'status'          => ['required', 'in:draft,published'],
        ]);

        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('organizer.events.index')
            ->with('status', "Event \"{$event->title}\" berhasil dibuat.");
    }

    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        $orders = $event->orders()->with('user')->latest()->paginate(10);

        $checkedInOrders = $event->orders()
            ->with('user')
            ->whereNotNull('checked_in_at')
            ->orderBy('checked_in_at', 'desc')
            ->get();

        $totalCheckedIn = $checkedInOrders->count();
        $totalPaid      = $event->orders()->where('status', 'paid')->count();

        return view('organizer.events.show', compact('event', 'orders', 'checkedInOrders', 'totalCheckedIn', 'totalPaid'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        $categories = Event::categories();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'banner_image'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:3072'],
            'category'        => ['required', 'string', 'in:' . implode(',', array_keys(Event::categories()))],
            'event_date'      => ['required', 'date'],
            'location'        => ['required', 'string', 'max:500'],
            'ticket_price'    => ['required', 'numeric', 'min:0'],
            'ticket_capacity' => ['required', 'integer', 'min:1'],
            'status'          => ['required', 'in:draft,published,cancelled'],
        ]);

        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('organizer.events.index')
            ->with('status', "Event \"{$event->title}\" berhasil diperbarui.");
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $title = $event->title;
        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('status', "Event \"{$title}\" berhasil dihapus.");
    }

    public function checkin(Event $event)
    {
        $this->authorizeEvent($event);

        $totalCheckedIn = Order::where('event_id', $event->id)
            ->whereNotNull('checked_in_at')
            ->count();

        $totalPaid = Order::where('event_id', $event->id)
            ->where('status', 'paid')
            ->count();

        return view('organizer.events.checkin', compact('event', 'totalCheckedIn', 'totalPaid'));
    }

    public function scan(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $request->validate(['order_code' => 'required|string']);

        $order = Order::where('order_code', $request->order_code)
            ->where('event_id', $event->id)
            ->with('user')
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'type'    => 'not_found',
                'message' => 'Tiket tidak ditemukan untuk event ini.',
            ], 404);
        }

        if ($order->status !== 'paid') {
            return response()->json([
                'success' => false,
                'type'    => 'not_paid',
                'message' => 'Tiket belum dibayar (status: ' . $order->status . ').',
            ], 422);
        }

        if ($order->checked_in_at) {
            return response()->json([
                'success'       => false,
                'type'          => 'already_used',
                'message'       => 'Tiket sudah digunakan!',
                'checked_in_at' => $order->checked_in_at->format('d M Y, H:i'),
                'order' => [
                    'order_code' => $order->order_code,
                    'attendee'   => $order->user->name,
                    'quantity'   => $order->quantity,
                ],
            ], 422);
        }

        $order->update(['checked_in_at' => now()]);

        return response()->json([
            'success' => true,
            'type'    => 'ok',
            'message' => 'Check-in berhasil!',
            'order'   => [
                'order_code'    => $order->order_code,
                'attendee'      => $order->user->name,
                'quantity'      => $order->quantity,
                'checked_in_at' => now()->format('d M Y, H:i'),
            ],
        ]);
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
