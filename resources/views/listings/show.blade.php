<x-app-layout>
    @section('title', 'View Listing')
    <div class="container my-4">
        <!-- Listing Images -->
        <div class="row mb-4">
            <div class="col-12">
                @if($listing->getMedia('images')->count() > 0)
                    <div id="listingCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($listing->getMedia('images') as $index => $media)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $media->getUrl('large') }}" class="d-block w-100"
                                         style="height: 400px; object-fit: cover;" alt="{{ $listing->title }}">
                                </div>
                            @endforeach
                        </div>
                        @if($listing->getMedia('images')->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <span class="text-muted">No Images Available</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <h1>{{ $listing->title }}</h1>
                <p class="text-muted mb-3">{{ $listing->address }}, {{ $listing->city }}, {{ $listing->country }}</p>

                <div class="d-flex gap-4 mb-4">
                    <span>{{ $listing->bedrooms }} bedrooms</span>
                    <span>{{ $listing->bathrooms }} bathrooms</span>
                    <span>{{ $listing->max_guests }} guests</span>
                </div>

                <div class="mb-4">
                    <h5>About this place</h5>
                    <p>{{ $listing->description }}</p>
                </div>

                @if($listing->amenities)
                    <div class="mb-4">
                        <h5>Amenities</h5>
                        <div class="row">
                            @foreach($listing->amenities as $amenity)
                                <div class="col-md-6 mb-2">
                                    <span class="badge bg-light text-dark">{{ $amenity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Map -->
                @if($listing->latitude && $listing->longitude)
                    <div class="mb-4">
                        <h5>Location</h5>
                        <div id="map" class="map rounded"></div>
                    </div>
                @endif

                <!-- Reviews -->
                @if($listing->reviews->count() > 0)
                    <div class="mb-4">
                        <h5>Reviews ({{ $listing->reviews->count() }})</h5>
                        @if($averageRating)
                            <p>Average Rating: {{ number_format($averageRating, 1) }}/5 ⭐</p>
                        @endif

                        @foreach($listing->reviews->take(5) as $review)
                            <div class="border-bottom py-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->user->name }}</strong>
                                    <small class="text-muted">{{ $review->created_at->format('M Y') }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">${{ $listing->price }}</h4>
                            <small class="text-muted">per night</small>
                        </div>

                        @auth
                            @if(auth()->user()->isGuest())
                                <form method="POST" action="{{ route('bookings.store', $listing) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Check-in</label>
                                        <input type="date" class="form-control" name="start_date"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Check-out</label>
                                        <input type="date" class="form-control" name="end_date"
                                               min="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Guests</label>
                                        <select class="form-control" name="guests" required>
                                            @for($i = 1; $i <= $listing->max_guests; $i++)
                                                <option value="{{ $i }}">{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Special Requests (Optional)</label>
                                        <textarea class="form-control" name="notes" rows="3"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-3">Book Now</button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    Switch to guest account to book this listing.
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-3">Login to Book</a>
                        @endauth

                        <div class="text-center">
                            <small class="text-muted">Hosted by {{ $listing->host->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($listing->latitude && $listing->longitude)
        @push('scripts')
        <script>
            // Initialize map
            var map = L.map('map').setView([{{ $listing->latitude }}, {{ $listing->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ' OpenStreetMap contributors'
            }).addTo(map);

            L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}])
                .addTo(map)
                .bindPopup('{{ $listing->title }}');
        </script>
        @endpush
    @endif
</x-app-layout>
