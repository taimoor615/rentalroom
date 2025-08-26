<x-app-layout>
    @section('title', 'Manage Bookings')

    <div class="container my-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage All Bookings</h2>
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Booking
                    </a>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Booking Type</label>
                                <select class="form-select" name="type">
                                    <option value="">All Types</option>
                                    <option value="regular" {{ request('type') === 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Admin Booking</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>All Bookings ({{ $bookings->total() }} total)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Property</th>
                                        <th>Guest</th>
                                        <th>Host</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Guests</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>#{{ $booking->id }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $booking->created_at->format('M j, Y') }}</small>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $booking->listing->title }}
                                                    <br>
                                                    <small class="text-muted">{{ $booking->listing->city }}, {{ $booking->listing->country }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($booking->isProxyBooking())
                                                    <div>
                                                        <strong>{{ $booking->guest_name ?? $booking->user->name }}</strong>
                                                        @if($booking->guest_email)
                                                            <br><small class="text-muted">{{ $booking->guest_email }}</small>
                                                        @endif
                                                        @if($booking->guest_phone)
                                                            <br><small class="text-muted">{{ $booking->guest_phone }}</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div>
                                                        <strong>{{ $booking->user->name }}</strong>
                                                        <br><small class="text-muted">{{ $booking->user->email }}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $booking->listing->host->name }}
                                                    <br>
                                                    <small class="text-muted">{{ $booking->listing->host->email }}</small>
                                                </div>
                                            </td>
                                            <td>{{ $booking->start_date->format('M j, Y') }}</td>
                                            <td>{{ $booking->end_date->format('M j, Y') }}</td>
                                            <td class="text-center">{{ $booking->guests }}</td>
                                            <td>
                                                <strong>${{ $booking->total_price }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $booking->days }} night{{ $booking->days > 1 ? 's' : '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($booking->isProxyBooking())
                                                    <span class="badge bg-info">Admin</span>
                                                    <br>
                                                    <small class="text-muted">by {{ $booking->bookedBy->name }}</small>
                                                @else
                                                    <span class="badge bg-secondary">Regular</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-sm" role="group">
                                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>

                                                    @if($booking->isPending())
                                                        <button type="button"
                                                                class="btn btn-outline-success btn-sm"
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')">
                                                            <i class="fas fa-check"></i> Confirm
                                                        </button>
                                                    @endif

                                                    @if($booking->isPending() || $booking->isConfirmed())
                                                        <button type="button"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'cancelled')">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                    @endif

                                                    @if($booking->isConfirmed() && $booking->end_date->isPast())
                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm"
                                                                onclick="updateBookingStatus({{ $booking->id }}, 'completed')">
                                                            <i class="fas fa-flag-checkered"></i> Complete
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="confirmDelete({{ $booking->id }})">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                    <p>No bookings found</p>
                                                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                                                        Create First Booking
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($bookings->hasPages())
                        <div class="card-footer">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Booking Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update this booking status?</p>
                    <form id="statusForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="statusInput" name="status">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('statusForm').submit()">
                        Confirm Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Warning:</strong> This action cannot be undone.</p>
                    <p>This will delete the booking and if the user has no other bookings, the user account will also be deleted.</p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm').submit()">
                        Delete Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateBookingStatus(bookingId, status) {
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            const form = document.getElementById('statusForm');
            const statusInput = document.getElementById('statusInput');

            form.action = `/admin/bookings/${bookingId}/status`;
            statusInput.value = status;

            modal.show();
        }

        function confirmDelete(bookingId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const form = document.getElementById('deleteForm');

            form.action = `/admin/bookings/${bookingId}`;
            modal.show();
        }
    </script>
    @endpush
</x-app-layout>
