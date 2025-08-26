<x-app-layout>
    @section('title', 'Payment Cancel')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <svg width="40" height="40" fill="white" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                </svg>
                            </div>
                        </div>

                        <h2 class="text-warning mb-3">Payment Cancelled</h2>
                        <p class="mb-4">Your payment was cancelled. Your booking is still pending and you can complete the payment anytime.</p>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>{{ $booking->listing->title }}</h5>
                                <p class="text-muted mb-2">{{ $booking->listing->city }}, {{ $booking->listing->country }}</p>
                                <p><strong>Dates:</strong> {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</p>
                                <p><strong>Total Amount:</strong> ${{ $booking->total_price }}</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('payment.create', $booking) }}" class="btn btn-success">Try Payment Again</a>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary">View Booking Details</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Go to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
