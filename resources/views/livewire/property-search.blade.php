<div>
    <!-- Search Input with Autocomplete -->
    <div class="mb-4 position-relative">
        <input
            type="text"
            class="form-control"
            wire:model.live.debounce.300ms="location"
            wire:focus="$set('showSuggestions', true)"
            wire:blur="hideSuggestions"
            placeholder="Where to?"
            autocomplete="off"
        >

        <!-- Suggestions Dropdown -->
        @if($showSuggestions && $suggestions->count() > 0)
            <div class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                @foreach($suggestions as $suggestion)
                    <div
                        class="px-3 py-2 border-bottom cursor-pointer suggestion-item"
                        style="cursor: pointer;"
                        wire:click="selectSuggestion('{{ $suggestion }}')"
                        onmouseover="this.style.backgroundColor='#f8f9fa'"
                        onmouseout="this.style.backgroundColor='white'"
                    >
                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                        {{ $suggestion }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Loading indicator -->
        <div wire:loading wire:target="location" class="mt-2">
            <small class="text-muted">
                <i class="fas fa-spinner fa-spin"></i> Searching...
            </small>
        </div>
    </div>

    <!-- Results -->
    <div wire:loading.remove wire:target="location">
        @if(isset($listings) && $listings->count() > 0)
            <div class="row">
                @foreach($listings as $listing)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if(isset($listing->image) && $listing->image)
                                <img src="{{ asset($listing->image) }}" class="card-img-top" alt="{{ $listing->title ?? 'Property' }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-home fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $listing->title ?? 'Property' }}</h5>
                                <p class="card-text flex-grow-1">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                    {{ $listing->country ?? 'Location not specified' }}
                                </p>
                                @if(isset($listing->price))
                                    <p class="card-text mt-auto">
                                        <strong class="text-success">${{ number_format($listing->price) }}</strong>
                                        <small class="text-muted">/ night</small>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $listings->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                @if($location)
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <h5>No properties found</h5>
                    <p class="mb-0">No properties found for "<strong>{{ $location }}</strong>". Try searching for a different location.</p>
                @else
                    <i class="fas fa-home fa-2x text-muted mb-2"></i>
                    <h5>Start your search</h5>
                    <p class="mb-0">Enter a location to find amazing properties.</p>
                @endif
            </div>
        @endif
    </div>

    <!-- JavaScript for better UX -->
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('hide-suggestions-delayed', function () {
                setTimeout(() => {
                    @this.set('showSuggestions', false);
                }, 200);
            });
        });
    </script>
</div>
