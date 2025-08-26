<x-app-layout>
    @section('title', 'Edit Listing')
    <div class="container my-4">
        <h1>Edit Listing</h1>

        <form method="POST" action="{{ route('listings.update', $listing) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $listing->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="5" required>{{ old('description', $listing->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price per night ($)</label>
                                        <input type="number" class="form-control" name="price" value="{{ old('price', $listing->price) }}" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Max Guests</label>
                                        <input type="number" class="form-control" name="max_guests" value="{{ old('max_guests', $listing->max_guests) }}" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bedrooms</label>
                                        <input type="number" class="form-control" name="bedrooms" value="{{ old('bedrooms', $listing->bedrooms) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bathrooms</label>
                                        <input type="number" class="form-control" name="bathrooms" value="{{ old('bathrooms', $listing->bathrooms) }}" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address', $listing->address) }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" value="{{ old('city', $listing->city) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control" name="country" value="{{ old('country', $listing->country) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amenities</label>
                                <div class="row">
                                    @php
                                        $amenitiesList = ['WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Air Conditioning', 'Heating', 'Washer', 'Dryer', 'TV', 'Hot Tub'];
                                        $selectedAmenities = old('amenities', $listing->amenities ?? []);
                                    @endphp
                                    @foreach($amenitiesList as $amenity)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                                       value="{{ $amenity }}" id="amenity_{{ $loop->index }}"
                                                       {{ in_array($amenity, $selectedAmenities) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="amenity_{{ $loop->index }}">
                                                    {{ $amenity }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Add New Images</label>
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                                <small class="text-muted">You can select multiple images to add</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Current Listing</h5>
                            <div class="mb-3">
                                @if($listing->getFirstMediaUrl('images'))
                                    <img src="{{ $listing->getFirstMediaUrl('images', 'thumb') }}"
                                         class="img-fluid rounded mb-2" alt="{{ $listing->title }}">
                                @endif
                                <p class="text-muted">{{ $listing->title }}</p>
                                <p class="text-muted small">{{ $listing->city }}, {{ $listing->country }}</p>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Listing</button>
                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-secondary w-100 mt-2">View Listing</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>

                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Current Images -->
        @if($listing->getMedia('images')->count() > 0)
            <div class="mb-3">
                <label class="form-label">Current Images</label>
                <div class="row">
                    @foreach($listing->getMedia('images') as $media)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ $media->getUrl('thumb') }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <form method="POST" action="{{ route('media.delete', $media->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                                onclick="return confirm('Are you sure you want to delete this image?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('listings.destroy', $listing) }}" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-25 bg-danger text-white mx-auto d-block"
                    onclick="return confirm('Are you sure you want to delete this listing? This action cannot be undone.')">
                Delete Listing
            </button>
        </form>
    </div>
</x-app-layout>
