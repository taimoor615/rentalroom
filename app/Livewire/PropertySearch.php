<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Listing; // Adjust model name as needed
use Livewire\WithPagination;

class PropertySearch extends Component
{
    use WithPagination;

    public $location = '';
    public $perPage = 6;
    public $showSuggestions = false;

    protected $queryString = ['title'];

    public function mount()
    {
        $this->location = request('title', '');
    }

    public function updatedLocation()
    {
        $this->resetPage(); // Reset pagination when search changes

        // Show suggestions only when there's some input and it's not too short
        $this->showSuggestions = !empty(trim($this->location)) && strlen(trim($this->location)) >= 2;
    }

    public function selectSuggestion($suggestion)
    {
        $this->location = $suggestion;
        $this->showSuggestions = false;
        $this->resetPage();
    }

    public function hideSuggestions()
    {
        // Small delay to allow clicking on suggestions
        $this->dispatch('hide-suggestions-delayed');
    }

    public function getSuggestions()
    {
        if (empty(trim($this->location)) || strlen(trim($this->location)) < 2) {
            return collect();
        }

        // Get unique locations, addresses, and cities that match the search
        $locations = Listing::where('title', 'LIKE', '%' . $this->location . '%')
            ->pluck('title')
            ->filter()
            ->unique()
            ->take(5);

        $cities = Listing::where('city', 'LIKE', '%' . $this->location . '%')
            ->pluck('city')
            ->filter()
            ->unique()
            ->take(5);

        $addresses = Listing::where('country', 'LIKE', '%' . $this->location . '%')
            ->pluck('country')
            ->filter()
            ->unique()
            ->take(3);

        return $locations->merge($cities)->merge($addresses)->unique()->take(8);
    }

    public function render()
    {
        $suggestions = $this->showSuggestions ? $this->getSuggestions() : collect();

        $listings = Listing::query()
            ->when($this->location, function ($query) {
                $query->where('title', 'LIKE', '%' . $this->location . '%')
                      ->orWhere('country', 'LIKE', '%' . $this->location . '%')
                      ->orWhere('city', 'LIKE', '%' . $this->location . '%');
            })
            ->paginate($this->perPage);

        return view('livewire.property-search', compact('listings', 'suggestions'));
    }
}
