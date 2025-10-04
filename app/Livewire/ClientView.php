<?php

namespace App\Livewire;

use App\Models\Profile;
use App\Models\Reviews;
use App\Models\Service;
use Livewire\Component;

class ClientView extends Component
{
    public Profile $profile;
    public $services;
    public $approvedReviews;

    // The component takes the slug directly from the route
    public function mount(string $slug)
    {
        // 1. Load the published profile or abort/fail gracefully
        $this->profile = Profile::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // 2. Load related data, initialized to empty collections for safety
        $this->services = Service::where('profile_id', $this->profile->id)
            ->where('is_available', true)
            ->get() ?? collect();

        $this->approvedReviews = Reviews::where('profile_id', $this->profile->id)
            ->where('is_approved', true)
            ->latest()
            ->get() ?? collect();
    }

    public function render()
    {
        // Renders the clean public view
        return view('livewire.client-view');
    }
}
