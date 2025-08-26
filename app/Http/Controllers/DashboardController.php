<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isHost()) {
            $totalListingsCount = $user->listings()->count();
            // $listings = $user->listings()->with('media')->latest()->get();
            $listings = $user->listings()
            ->with('media')
            ->latest()
            ->paginate(5);
            $bookings = collect();
            foreach ($listings as $listing) {
                $bookings = $bookings->merge($listing->bookings()->with('user')->latest()->get());
            }

            return view('dashboard.host', compact('listings', 'bookings','totalListingsCount'));
        } elseif($user->isAdmin()){
            return view('dashboard.admin');
        }
        else {
            $bookings = $user->bookings()->with('listing.media')->latest()->get();
            return view('dashboard.guest', compact('bookings'));
        }
    }
}
