<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        // Check if user can review this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'You can only review your own bookings.');
        }

        if (!$booking->isCompleted() && !$booking->end_date->isPast()) {
            return back()->withErrors(['error' => 'You can only review after your stay is completed.']);
        }

        if ($booking->review) {
            return back()->withErrors(['error' => 'You have already reviewed this booking.']);
        }

        Review::create([
            'listing_id' => $validated['listing_id'],
            'user_id' => auth()->id(),
            'booking_id' => $validated['booking_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
