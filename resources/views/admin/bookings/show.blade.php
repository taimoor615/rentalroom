<x-app-layout>
    @section('title', 'Booking Details - Admin')

    <div class="container my-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Booking Details #{{ $booking->id }}</h4>
                            <div>
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'secondary')) }} me-2">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                @if($booking->isProxyBooking())
                                    <span class="badge bg-info">Admin Booking</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Admin Booking Alert -->
                        @if($booking->isProxyBooking())
                            <div class="alert alert-info">
                                <strong><i class="fas fa-user-shield"></i> Admin Booking:</strong>
                                This booking was created by <strong>{{ $booking->bookedBy->name }}</strong>
                                @if($booking->guest_name)
                                    for <strong>{{ $booking->guest_name }}</strong>
                                @endif
                                <br>
                                <small class="text-muted">Created on {{ $booking->created_at->format('F j, Y g:i A') }}</small>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                @if($booking->listing->getFirstMediaUrl('images'))
                                    <img src="{{ $booking->listing->getFirstMediaUrl('images', 'large') }}"
                                         class="img-fluid rounded mb-3" alt="{{ $booking->listing->title }}">
                                @endif

                                <h5>{{ $booking->listing->title }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $booking->listing->address }}, {{ $booking->listing->city }}, {{ $booking->listing->country }}
                                </p>

                                <div class="mb-3">
                                    <strong>Host:</strong>
                                    <a href="mailto:{{ $booking->listing->host->email }}">{{ $booking->listing->host->name }}</a>
                                    @if($booking->listing->host->phone)
                                        <br><small class="text-muted">{{ $booking->listing->host->phone }}</small>
                                    @endif
                                </div>

                                <!-- Property Details -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Property Details</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Bedrooms:</small><br>
                                                <strong>{{ $booking->listing->bedrooms }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Bathrooms:</small><br>
                                                <strong>{{ $booking->listing->bathrooms }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Max Guests:</small><br>
                                                <strong>{{ $booking->listing->max_guests }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Price/Night:</small><br>
                                                <strong>${{ $booking->listing->price }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Booking Information</h6>

                                <!-- Guest Information -->
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6>Guest Details</h6>
                                        @if($booking->isProxyBooking())
                                            <div class="mb-2">
                                                <strong>Guest Name:</strong> {{ $booking->guest_name ?? 'N/A' }}
                                            </div>
                                            @if($booking->guest_email)
                                                <div class="mb-2">
                                                    <strong>Email:</strong>
                                                    <a href="mailto:{{ $booking->guest_email }}">{{ $booking->guest_email }}</a>
                                                </div>
                                            @endif
                                            @if($booking->guest_phone)
                                                <div class="mb-2">
                                                    <strong>Phone:</strong> {{ $booking->guest_phone }}
                                                </div>
                                            @endif
                                            @if($booking->user)
                                                <div class="mb-2">
                                                    <strong>Registered User:</strong>
                                                    <a href="mailto:{{ $booking->user->email }}">{{ $booking->user->name }}</a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="mb-2">
                                                <strong>Guest:</strong>
                                                <a href="mailto:{{ $booking->user->email }}">{{ $booking->user->name }}</a>
                                            </div>
                                            @if($booking->user->phone)
                                                <div class="mb-2">
                                                    <strong>Phone:</strong> {{ $booking->user->phone }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- Booking Dates -->
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
                                    <strong>Total Price:</strong>
                                    <span class="h5 text-success">${{ $booking->total_price }}</span>
                                </div>

                                @if($booking->notes)
                                    <div class="mb-3">
                                        <strong>Special Requests:</strong>
                                        <div class="alert alert-secondary">
                                            {{ $booking->notes }}
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <strong>Booking Date:</strong> {{ $booking->created_at->format('F j, Y g:i A') }}
                                </div>

                                @if($booking->stripe_payment_intent_id)
                                    <div class="mb-2">
                                        <strong>Payment ID:</strong>
                                        <code>{{ $booking->stripe_payment_intent_id }}</code>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Timeline -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Booking Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="fas fa-plus-circle text-primary"></i>
                                <div>
                                    <strong>Booking Created</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->created_at->format('F j, Y g:i A') }}</small>
                                    @if($booking->isProxyBooking())
                                        <br><small>by {{ $booking->bookedBy->name }} (Admin)</small>
                                    @endif
                                </div>
                            </div>

                            @if($booking->isConfirmed())
                                <div class="timeline-item">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <div>
                                        <strong>Booking Confirmed</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->updated_at->format('F j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            @endif

                            @if($booking->isCancelled())
                                <div class="timeline-item">
                                    <i class="fas fa-times-circle text-danger"></i>
                                    <div>
                                        <strong>Booking Cancelled</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->updated_at->format('F j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            @endif

                            @if($booking->isCompleted())
                                <div class="timeline-item">
                                    <i class="fas fa-flag-checkered text-secondary"></i>
                                    <div>
                                        <strong>Booking Completed</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking->updated_at->format('F j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Admin Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5>Admin Actions</h5>
                    </div>
                    <div class="card-body">
                        @if($booking->isPending())
                            <button type="button" class="btn btn-success w-100 mb-2"
                                    onclick="updateStatus('{{ $booking->id }}', 'confirmed')">
                                <i class="fas fa-check"></i> Confirm Booking
                            </button>
                        @endif

                        @if($booking->isPending() || $booking->isConfirmed())
                            <button type="button" class="btn btn-danger w-100 mb-2"
                                    onclick="updateStatus('{{ $booking->id }}', 'cancelled')">
                                <i class="fas fa-times"></i> Cancel Booking
                            </button>
                        @endif

                        @if($booking->isConfirmed() && $booking->end_date->isPast())
                            <button type="button" class="btn btn-secondary w-100 mb-2"
                                    onclick="updateStatus('{{ $booking->id }}', 'completed')">
                                <i class="fas fa-flag-checkered"></i> Mark as Completed
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger w-100 mb-2"
                                onclick="confirmDelete({{ $booking->id }})">
                            <i class="fas fa-trash"></i> Delete Booking
                        </button>

                        <hr>

                        <a href="{{ route('listings.show', $booking->listing) }}"
                           class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-home"></i> View Listing
                        </a>

                        <a href="{{ route('admin.bookings.index') }}"
                           class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-list"></i> Back to All Bookings
                        </a>

                        <a href="{{ route('admin.bookings.create') }}"
                           class="btn btn-outline-info w-100">
                            <i class="fas fa-plus"></i> Create New Booking
                        </a>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Quick Contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Host Contact:</strong>
                            <br>
                            <a href="mailto:{{ $booking->listing->host->email }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope"></i> Email Host
                            </a>
                        </div>

                        @if(!$booking->isProxyBooking())
                            <div class="mb-3">
                                <strong>Guest Contact:</strong>
                                <br>
                                <a href="mailto:{{ $booking->user->email }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-envelope"></i> Email Guest
                                </a>
                            </div>
                        @else
                            @if($booking->guest_email)
                                <div class="mb-3">
                                    <strong>Guest Contact:</strong>
                                    <br>
                                    <a href="mailto:{{ $booking->guest_email }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-envelope"></i> Email Guest
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Reviews Section (if completed) -->
                @if($booking->isCompleted() && $booking->review)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Guest Review</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">{{ $booking->review->rating }}/5</span>
                            </div>
                            @if($booking->review->comment)
                                <p class="mb-0">{{ $booking->review->comment }}</p>
                            @endif
                        </div>
                    </div>
                @endif
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

    @push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .timeline-item i {
            position: absolute;
            left: -30px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -21px;
            top: 20px;
            width: 2px;
            height: calc(100% + 20px);
            background-color: #dee2e6;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function updateStatus(bookingId, status) {
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
