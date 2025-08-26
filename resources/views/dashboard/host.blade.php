<x-app-layout>
    @section('title', 'Host Dashboard')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Host Dashboard</h1>
            <a href="{{ route('listings.create') }}" class="btn btn-primary">Add New Listing</a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $totalListingsCount }}</h3>
                        <p class="text-muted">Total Listings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                        <p class="text-muted">Confirmed Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $bookings->where('status', 'pending')->count() }}</h3>
                        <p class="text-muted">Pending Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">${{ $bookings->where('status', 'confirmed')->sum('total_price') }}</h3>
                        <p class="text-muted">Total Earnings</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- My Listings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">My Listings</h5>
                    </div>
                    <div class="card-body">
                        @if($listings->count() > 0)
                            {{-- @foreach($listings->take(5) as $listing) --}}
                            @foreach($listings as $listing)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>{{ $listing->title }}</strong>
                                        <br>
                                        <small class="text-muted">${{ $listing->price }}/night</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('listings.show', $listing) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                        <a href="{{ route('listings.edit', $listing) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No listings yet. <a href="{{ route('listings.create') }}">Create your first listing</a></p>
                        @endif
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-2 mb-3">
                        {{ $listings->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        @if($bookings->count() > 0)
                            @foreach($bookings->take(5) as $booking)
                                <div class="py-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $booking->listing->title }}</strong>
                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $booking->user->name }} •
                                        {{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d, Y') }} •
                                        ${{ $booking->total_price }}
                                    </small>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No bookings yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
