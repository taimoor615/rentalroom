<x-app-layout>
    @section('title', 'Guest Dashboard')
    <div class="container my-4">
        <h1>Guest Dashboard</h1>
        <p class="text-muted mb-4">Manage your bookings and travel history</p>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $bookings->count() }}</h3>
                        <p class="text-muted">Total Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ $bookings->where('status', 'confirmed')->count() }}</h3>
                        <p class="text-muted">Confirmed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $bookings->where('status', 'pending')->count() }}</h3>
                        <p class="text-muted">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">${{ $bookings->sum('total_price') }}</h3>
                        <p class="text-muted">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Bookings -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Bookings</h5>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="row">
                        @foreach($bookings as $booking)
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    @if($booking->listing->getFirstMediaUrl('images'))
                                        <img src="{{ $booking->listing->getFirstMediaUrl('images', 'thumb') }}"
                                             class="card-img-top" style="height: 200px; object-fit: cover;"
                                             alt="{{ $booking->listing->title }}">
                                    @endif

                                    <div class="card-body">
                                        <h6 class="card-title">{{ $booking->listing->title }}</h6>
                                        <p class="text-muted small">{{ $booking->listing->city }}, {{ $booking->listing->country }}</p>

                                        <div class="mb-2">
                                            <strong>Dates:</strong> {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
                                        </div>

                                        <div class="mb-2">
                                            <strong>Guests:</strong> {{ $booking->guests }}
                                        </div>

                                        <div class="mb-3">
                                            <strong>Total:</strong> ${{ $booking->total_price }}
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>

                                            <div>
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>

                                                @if($booking->isPending())
                                                    <a href="{{ route('payment.create', $booking) }}" class="btn btn-sm btn-success">Pay Now</a>
                                                @endif

                                                @if($booking->isPending() || ($booking->isConfirmed() && $booking->start_date->isFuture()))
                                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <h5 class="text-muted">No bookings yet</h5>
                        <p>Start exploring amazing places to stay!</p>
                        <a href="{{ route('listings.index') }}" class="btn btn-primary">Browse Listings</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
