<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with('host', 'media')->where('is_active', true);

        // Search by location
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('country', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%");
            });
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by guests
        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        $listings = $query->paginate(12);

        return view('listings.index', compact('listings'));
    }

    public function show(Listing $listing)
    {
        $listing->load('host', 'media', 'reviews.user');
        $averageRating = $listing->averageRating();

        return view('listings.show', compact('listing', 'averageRating'));
    }

    public function create()
    {
        $this->authorize('create', Listing::class);
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Listing::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
            'amenities' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['host_id'] = Auth::id();

        $listing = Listing::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $listing->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        return view('listings.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
            'amenities' => 'array',
        ]);

        $listing->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $listing->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        $listing->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Listing deleted successfully!');
    }

    public function deleteMedia($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $media->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
