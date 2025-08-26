<x-app-layout>
@section('title', 'Create Booking')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h2>Admin Dashboard</h2>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Bookings</h5>
                            <h3>{{ App\Models\Booking::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Active Listings</h5>
                            <h3>{{ App\Models\Listing::where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h3>{{ App\Models\User::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Proxy Bookings</h5>
                            <h3>{{ App\Models\Booking::whereNotNull('booked_by')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-block w-100">
                                        Create New Booking
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-info btn-block w-100">
                                        Manage Bookings
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('listings.index') }}" class="btn btn-success btn-block w-100">
                                        View All Listings
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="btn btn-warning btn-block w-100">
                                        User Management
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Bookings</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recentBookings = App\Models\Booking::with(['listing', 'user', 'bookedBy'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Property</th>
                                            <th>Guest</th>
                                            <th>Dates</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentBookings as $booking)
                                            <tr>
                                                <td>#{{ $booking->id }}</td>
                                                <td>{{ $booking->listing->title }}</td>
                                                <td>
                                                    @if($booking->isProxyBooking())
                                                        {{ $booking->guest_name ?? $booking->user->name }}
                                                        <small class="text-muted">(by {{ $booking->bookedBy->name }})</small>
                                                    @else
                                                        {{ $booking->user->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $booking->start_date->format('M d') }} -
                                                    {{ $booking->end_date->format('M d, Y') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($booking->isProxyBooking())
                                                        <span class="badge bg-info">Admin Booking</span>
                                                    @else
                                                        <span class="badge bg-secondary">Regular</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No bookings found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
