<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'guests' => 'required|integer|min:1|max:' . $listing->max_guests,
            'notes' => 'nullable|string|max:500',
        ]);

        // Check availability
        if (!$listing->isAvailable($validated['start_date'], $validated['end_date'])) {
            return back()->withErrors(['dates' => 'The selected dates are not available.']);
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $listing->price;

        $booking = Booking::create([
            'listing_id' => $listing->id,
            'user_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'guests' => $validated['guests'],
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->route('payment.create', $booking)
            ->with('success', 'Booking created! Please complete payment.');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('listing.media', 'listing.host');

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        if ($booking->isConfirmed() && $booking->start_date->isPast()) {
            return back()->withErrors(['error' => 'Cannot cancel a booking that has already started.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
