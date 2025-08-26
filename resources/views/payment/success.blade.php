<x-app-layout>
    @section('title', 'Payment Successful')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <svg width="40" height="40" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <p class="mb-4">Your booking has been confirmed. You'll receive a confirmation email shortly.</p>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>{{ $booking->listing->title }}</h5>
                                <p class="text-muted mb-2">{{ $booking->listing->city }}, {{ $booking->listing->country }}</p>
                                <p><strong>Dates:</strong> {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</p>
                                <p><strong>Total Paid:</strong> ${{ $booking->total_price }}</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary">View Booking Details</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Go to Dashboard</a>
                            <a href="{{ route('listings.index') }}" class="btn btn-outline-primary">Browse More Listings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
