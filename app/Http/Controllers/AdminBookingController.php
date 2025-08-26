<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });

        $this->authorizeResource(Booking::class, 'booking');
    }

    public function index()
    {
        $bookings = Booking::with(['listing', 'user', 'bookedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $listings = Listing::where('is_active', true)->get();
        $users = User::where('role', 'guest')->get();

        return view('admin.bookings.create', compact('listings', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'booking_type' => 'required|in:existing_user,new_guest',
            'user_id' => 'nullable|required_if:booking_type,existing_user|exists:users,id',
            'guest_name' => 'nullable|required_if:booking_type,new_guest|string|max:255',
            'guest_email' => 'nullable|required_if:booking_type,new_guest|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'guests' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        // Check availability
        if (!$listing->isAvailable($request->start_date, $request->end_date)) {
            return back()->withErrors(['dates' => 'The selected dates are not available.']);
        }

        if ($request->booking_type === 'existing_user') {
            $userId = $request->user_id;
        } else {
            $user = User::create([
            'name'     => $request->guest_name,
            'email'    => $request->guest_email,
            'phone'    => $request->guest_phone,
            'password' => Hash::make('password'), // default password
            'role'     => 'guest',
            ]);

            $userId = $user->id;
        }

        // Calculate total price
        $days = now()->parse($request->start_date)->diffInDays($request->end_date);
        $totalPrice = $listing->price * $days;

        $bookingData = [
            'listing_id'  => $request->listing_id,
            'user_id'     => $userId,
            'booked_by'   => auth()->id(),
            'guest_name'  => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => 'pending', // Always pending
            'total_price' => $totalPrice,
            'guests'      => $request->guests,
            'notes'       => $request->notes,
        ];

        Booking::create($bookingData);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        // Load all necessary relationships
        $booking->load([
            'listing.host',
            'listing.media',
            'user',
            'bookedBy',
            'review'
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        // Check if this is a proxy booking (admin created for new guest)
        if ($booking->isProxyBooking() && !$booking->user_id && $booking->guest_email) {
            // This is an admin booking for a new guest, just delete the booking
            $booking->delete();
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Admin booking deleted successfully!');
        }

        // If booking has a user and it's the only booking for that user, delete user too
        if ($booking->user_id) {
            $user = $booking->user;
            $userBookingsCount = $user->bookings()->count();

            if ($userBookingsCount === 1) {
                // This is the only booking for this user, delete user and booking
                $booking->delete();
                $user->delete();
                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Booking and associated user deleted successfully!');
            } else {
                // User has other bookings, just delete this booking
                $booking->delete();
                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Booking deleted successfully!');
            }
        }

        // Fallback: just delete the booking
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $this->authorize('updateStatus', $booking);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', "Booking status updated from {$oldStatus} to {$request->status}");
    }

}
