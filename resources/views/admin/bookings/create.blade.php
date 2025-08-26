<x-app-layout>
@section('title', 'Create Booking')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Booking</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.bookings.store') }}">
                        @csrf

                        <!-- Listing Selection -->
                        <div class="mb-3">
                            <label for="listing_id" class="form-label">Property</label>
                            <select class="form-select @error('listing_id') is-invalid @enderror"
                                    id="listing_id" name="listing_id" required>
                                <option value="">Select a property</option>
                                @foreach($listings as $listing)
                                    <option value="{{ $listing->id }}"
                                            {{ old('listing_id') == $listing->id ? 'selected' : '' }}>
                                        {{ $listing->title }} - ${{ $listing->price }}/night
                                    </option>
                                @endforeach
                            </select>
                            @error('listing_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Booking Type -->
                        <div class="mb-3">
                            <label class="form-label">Booking For</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="booking_type"
                                               id="existing_user" value="existing_user"
                                               {{ old('booking_type', 'existing_user') == 'existing_user' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="existing_user">
                                            Existing User
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="booking_type"
                                               id="new_guest" value="new_guest"
                                               {{ old('booking_type') == 'new_guest' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="new_guest">
                                            New Guest
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing User Selection -->
                        <div class="mb-3" id="existing_user_section">
                            <label for="user_id" class="form-label">Select User</label>
                            <select class="form-select @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Guest Information -->
                        <div id="new_guest_section" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="guest_name" class="form-label">Guest Name</label>
                                        <input type="text" class="form-control @error('guest_name') is-invalid @enderror"
                                               id="guest_name" name="guest_name" value="{{ old('guest_name') }}">
                                        @error('guest_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="guest_email" class="form-label">Guest Email</label>
                                        <input type="email" class="form-control @error('guest_email') is-invalid @enderror"
                                               id="guest_email" name="guest_email" value="{{ old('guest_email') }}">
                                        @error('guest_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="guest_phone" class="form-label">Guest Phone (Optional)</label>
                                <input type="text" class="form-control @error('guest_phone') is-invalid @enderror"
                                       id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}">
                                @error('guest_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guests" class="form-label">Number of Guests</label>
                                    <input type="number" class="form-control @error('guests') is-invalid @enderror"
                                           id="guests" name="guests" value="{{ old('guests', 1) }}" min="1" required>
                                    @error('guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const existingUserRadio = document.getElementById('existing_user');
    const newGuestRadio = document.getElementById('new_guest');
    const existingUserSection = document.getElementById('existing_user_section');
    const newGuestSection = document.getElementById('new_guest_section');

    function toggleSections() {
        if (existingUserRadio.checked) {
            existingUserSection.style.display = 'block';
            newGuestSection.style.display = 'none';
        } else {
            existingUserSection.style.display = 'none';
            newGuestSection.style.display = 'block';
        }
    }

    existingUserRadio.addEventListener('change', toggleSections);
    newGuestRadio.addEventListener('change', toggleSections);

    // Initialize on page load
    toggleSections();

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').setAttribute('min', today);

    // Update end date minimum when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        startDate.setDate(startDate.getDate() + 1);
        document.getElementById('end_date').setAttribute('min', startDate.toISOString().split('T')[0]);
    });
});
</script>
</x-app-layout>
