<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        $this->authorize('pay', $booking);

        if (!$booking->isPending()) {
            return redirect()->route('bookings.show', $booking)
                ->withErrors(['error' => 'This booking cannot be paid for.']);
        }

        Stripe::setApiKey(config('cashier.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $booking->listing->title,
                        'description' => "Booking from {$booking->start_date->format('M d, Y')} to {$booking->end_date->format('M d, Y')}",
                    ],
                    'unit_amount' => $booking->total_price * 100, // Stripe expects cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', $booking),
            'cancel_url' => route('payment.cancel', $booking),
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);

        return view('payment.success', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        return view('payment.cancel', compact('booking'));
    }
}
