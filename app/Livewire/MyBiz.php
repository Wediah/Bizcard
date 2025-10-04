<?php

namespace App\Livewire;

use App\Models\Profile;
use App\Models\Reviews;
use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;


class MyBiz extends Component
{
    public $profile;
    public $services;
    public $reviews;
    public $approvedReviews;
    public ?string $publicUrl = null;
    public bool $isAdminView = false;

    public function mount($slug = null): void
    {
        // If slug is provided, show that business (public view)
        if ($slug) {
            $this->profile = Profile::where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();
            $this->isAdminView = false;
        }
        // If no slug and user is logged in, show their own business (admin view)
        elseif (auth()->check()) {
            $this->profile = Profile::where('user_id', auth()->id())->first();
            $this->isAdminView = true;

            if (!$this->profile) {
                // Redirect to create profile if user doesn't have one
                session()->flash('error', 'Please create a business profile first.');
                // You might want to redirect to profile creation page here
                // return redirect()->route('buzprofile');
            }
        }
        // No slug and no auth - show error
        else {
            abort(404, 'Business not found.');
        }

        // Initialize collections
        $this->services = collect();
        $this->reviews = collect();
        $this->approvedReviews = collect();
        $this->publicUrl = null;

        if ($this->profile) {
            // Load services
            $this->services = Service::where('profile_id', $this->profile->id)->get();

            // Load reviews - only approved ones for public view
            if ($this->isAdminView) {
                // Admin sees all reviews (approved and unapproved)
                $this->reviews = Reviews::where('profile_id', $this->profile->id)
                    ->with('user')
                    ->latest()
                    ->get();
                $this->approvedReviews = $this->reviews->where('is_approved', true);
            } else {
                // Public only sees approved reviews
                $this->approvedReviews = Reviews::where('profile_id', $this->profile->id)
                    ->where('is_approved', true)
                    ->with('user')
                    ->latest()
                    ->get();
            }

            // Generate public URL
            if ($this->profile->slug) {
                $this->publicUrl = route('business.show', ['slug' => $this->profile->slug]);
            }
        }
    }

    public function render()
    {
        return view('livewire.my-biz');
    }
}
