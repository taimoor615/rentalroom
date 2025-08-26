<x-app-layout>
    @section('title', 'Listings')
    <!-- Hero Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="text-center mb-4">Find Your Perfect Stay</h1>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('listings.index') }}" class="card p-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="min_price"
                                       placeholder="Min Price" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" name="max_price"
                                       placeholder="Max Price" value="{{ request('max_price') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                            <div class="col-md-12">
                                 {{-- @livewire('property-search') --}}
                                <input type="text" class="form-control" name="location"
                                       placeholder="Where to?" value="{{ request('location') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings Grid -->
    <div class="container my-5">
        @if($listings->count() > 0)
            <div class="row g-4">
                @foreach($listings as $listing)
                    <div class="col-lg-4 col-md-6">
                        <div class="card listing-card h-100">
                            @if($listing->getFirstMediaUrl('images'))
                                <img src="{{ $listing->getFirstMediaUrl('images', 'thumb') }}"
                                     class="card-img-top listing-image" alt="{{ $listing->title }}">
                            @else
                                <div class="card-img-top listing-image bg-light d-flex align-items-center justify-content-center">
                                    <span class="text-muted">No Image</span>
                                </div>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $listing->title }}</h5>
                                <p class="text-muted small">{{ $listing->city }}, {{ $listing->country }}</p>
                                <p class="card-text">{{ Str::limit($listing->description, 100) }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-primary">${{ $listing->price }}</span>
                                        <small class="text-muted">/ night</small>
                                    </div>
                                    <div class="text-muted small">
                                        {{ $listing->bedrooms }}br • {{ $listing->max_guests }} guests
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent">
                                <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $listings->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h3 class="text-muted">No listings found</h3>
                <p>Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</x-app-layout>
