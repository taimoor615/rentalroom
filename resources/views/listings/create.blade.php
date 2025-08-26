<x-app-layout>
    @section('title', 'Create Listing')
    <div class="container my-4">
        <h1>Create New Listing</h1>

        <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price per night ($)</label>
                                        <input type="number" class="form-control" name="price" value="{{ old('price') }}" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Max Guests</label>
                                        <input type="number" class="form-control" name="max_guests" value="{{ old('max_guests', 2) }}" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bedrooms</label>
                                        <input type="number" class="form-control" name="bedrooms" value="{{ old('bedrooms', 1) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bathrooms</label>
                                        <input type="number" class="form-control" name="bathrooms" value="{{ old('bathrooms', 1) }}" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control" name="country" value="{{ old('country') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amenities</label>
                                <div class="row">
                                    @php
                                        $amenitiesList = ['WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Air Conditioning', 'Heating', 'Washer', 'Dryer', 'TV', 'Hot Tub'];
                                    @endphp
                                    @foreach($amenitiesList as $amenity)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                                       value="{{ $amenity }}" id="amenity_{{ $loop->index }}">
                                                <label class="form-check-label" for="amenity_{{ $loop->index }}">
                                                    {{ $amenity }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Images</label>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                <small class="text-muted">You can select multiple images</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Preview</h5>
                            <p class="text-muted">Your listing will appear like this to guests.</p>
                            <button type="submit" class="btn btn-primary w-100">Create Listing</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
