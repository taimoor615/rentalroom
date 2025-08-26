<x-app-layout>
    @section('title', 'Booked Listing')
    <div class="container my-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Details</h4>
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }} float-end">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if($booking->listing->getFirstMediaUrl('images'))
                                    <img src="{{ $booking->listing->getFirstMediaUrl('images', 'large') }}"
                                         class="img-fluid rounded mb-3" alt="{{ $booking->listing->title }}">
                                @endif

                                <h5>{{ $booking->listing->title }}</h5>
                                <p class="text-muted">{{ $booking->listing->address }}, {{ $booking->listing->city }}, {{ $booking->listing->country }}</p>

                                <div class="mb-3">
                                    <strong>Host:</strong> {{ $booking->listing->host->name }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Booking Information</h6>

                                <div class="mb-2">
                                    <strong>Check-in:</strong> {{ $booking->start_date->format('l, F j, Y') }}
                                </div>

                                <div class="mb-2">
                                    <strong>Check-out:</strong> {{ $booking->end_date->format('l, F j, Y') }}
                                </div>

                                <div class="mb-2">
                                    <strong>Duration:</strong> {{ $booking->days }} night{{ $booking->days > 1 ? 's' : '' }}
                                </div>

                                <div class="mb-2">
                                    <strong>Guests:</strong> {{ $booking->guests }}
                                </div>

                                <div class="mb-3">
                                    <strong>Total Price:</strong> ${{ $booking->total_price }}
                                </div>

                                @if($booking->notes)
                                    <div class="mb-3">
                                        <strong>Special Requests:</strong>
                                        <p class="text-muted">{{ $booking->notes }}</p>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <strong>Booking Date:</strong> {{ $booking->created_at->format('F j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>

                    <div class="card-body">
                        @if($booking->isPending())
                            <a href="{{ route('payment.create', $booking) }}" class="btn btn-success w-100 mb-2">
                                Complete Payment
                            </a>
                        @endif

                        @if($booking->isPending() || ($booking->isConfirmed() && $booking->start_date->isFuture()))
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger w-100 mb-2"
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('listings.show', $booking->listing) }}" class="btn btn-outline-primary w-100 mb-2">
                            View Listing
                        </a>

                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100">
                            Back to Dashboard
                        </a>
                    </div>

                    <!-- Add this section in your booking details -->
                    @if($booking->isProxyBooking())
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Admin Booking:</strong> This booking was created by {{ $booking->bookedBy->name }}
                                @if($booking->guest_name)
                                    for {{ $booking->guest_name }}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @if($booking->isConfirmed() && $booking->end_date->isPast() && !$booking->review)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Leave a Review</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('reviews.store') }}">
                                @csrf
                                <input type="hidden" name="listing_id" value="{{ $booking->listing_id }}">
                                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <select class="form-control" name="rating" required>
                                        <option value="">Select Rating</option>
                                        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                        <option value="4">⭐⭐⭐⭐ Very Good</option>
                                        <option value="3">⭐⭐⭐ Good</option>
                                        <option value="2">⭐⭐ Fair</option>
                                        <option value="1">⭐ Poor</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Comment (Optional)</label>
                                    <textarea class="form-control" name="comment" rows="3"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </x-app-layout>
