<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('event')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $event = Event::findOrFail($validated['event_id']);

        if ($event->status !== 'published') {
            return back()->with('error', 'Event tidak tersedia.');
        }

        if ($event->remainingCapacity() < $validated['quantity']) {
            return back()->with('error', 'Tiket tidak mencukupi. Sisa: ' . $event->remainingCapacity());
        }

        $existing = Order::where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Kamu sudah memesan tiket untuk event ini.');
        }

        $order = Order::create([
            'user_id'     => $request->user()->id,
            'event_id'    => $event->id,
            'quantity'    => $validated['quantity'],
            'total_price' => $event->ticket_price * $validated['quantity'],
            'status'      => 'pending',
            'order_code'  => Order::generateCode(),
        ]);

        // Event gratis → langsung paid, skip payment
        if ($event->isFree()) {
            $order->update(['status' => 'paid']);
            return redirect()->route('orders.show', $order)
                ->with('status', 'Tiket gratis berhasil didapatkan!');
        }

        return redirect()->route('orders.show', $order)
            ->with('status', 'Pesanan berhasil dibuat! Selesaikan pembayaran.');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('event.organizer');

        return view('orders.show', compact('order'));
    }

    public function checkout(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Order ini sudah diproses.'], 400);
        }

        $this->configureMidtrans();

        $params = [
            'transaction_details' => [
                'order_id'    => $order->order_code,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
            ],
            'item_details' => [
                [
                    'id'       => 'EVT-' . $order->event_id,
                    'price'    => (int) $order->event->ticket_price,
                    'quantity' => $order->quantity,
                    'name'     => mb_substr($order->event->title, 0, 50),
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function notification(Request $request)
    {
        $this->configureMidtrans();

        $notification      = new Notification();
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $orderId           = $notification->order_id;

        $order = Order::where('order_code', $orderId)->first();

        if (! $order) {
            return response('OK', 200);
        }

        if ($transactionStatus === 'capture') {
            $order->update(['status' => $fraudStatus === 'accept' ? 'paid' : 'pending']);
        } elseif ($transactionStatus === 'settlement') {
            $order->update(['status' => 'paid']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'cancelled']);
        }

        return response('OK', 200);
    }

    public function verify(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status === 'paid') {
            return response()->json(['status' => 'paid']);
        }

        $this->configureMidtrans();

        try {
            $status = Transaction::status($order->order_code);
            $transactionStatus = $status->transaction_status;
            $fraudStatus       = $status->fraud_status ?? 'accept';

            if ($transactionStatus === 'capture') {
                $newStatus = $fraudStatus === 'accept' ? 'paid' : 'pending';
            } elseif ($transactionStatus === 'settlement') {
                $newStatus = 'paid';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newStatus = 'cancelled';
            } else {
                $newStatus = 'pending';
            }

            $order->update(['status' => $newStatus]);

            return response()->json(['status' => $newStatus]);
        } catch (\Exception $e) {
            return response()->json(['status' => $order->status]);
        }
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya order pending yang bisa dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')
            ->with('status', 'Order berhasil dibatalkan.');
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }
}
