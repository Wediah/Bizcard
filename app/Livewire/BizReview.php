<?php
namespace App\Livewire;

use App\Models\Profile;
use App\Models\Reviews;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class BizReview extends Component
{
    use WithPagination;

    public $profile;
    public $selectedReview = null;
    public $businessResponse = '';

    public function mount(): void
    {
        $this->profile = Profile::where('user_id', auth()->id())->first();
    }

    public function approveReview($reviewId): void
    {
        $review = Reviews::find($reviewId);
        if ($review && $review->profile_id === $this->profile->id) {
            $review->update(['is_approved' => true]);
            session()->flash('message', 'Review approved successfully!');
        }
    }

    public function rejectReview($reviewId): void
    {
        $review = Reviews::find($reviewId);
        if ($review && $review->profile_id === $this->profile->id) {
            $review->delete();
            session()->flash('message', 'Review rejected and deleted!');
        }
    }

    public function setResponse($reviewId): void
    {
        $this->selectedReview = Review::find($reviewId);
        $this->businessResponse = $this->selectedReview->business_response ?? '';
    }

    public function submitResponse(): void
    {
        $this->validate([
            'businessResponse' => 'required|min:5|max:500'
        ]);

        if ($this->selectedReview) {
            $this->selectedReview->update([
                'business_response' => $this->businessResponse
            ]);

            $this->reset(['selectedReview', 'businessResponse']);
            session()->flash('message', 'Response submitted successfully!');
        }
    }

    public function cancelResponse(): void
    {
        $this->reset(['selectedReview', 'businessResponse']);
    }

    public function deleteReview($reviewId): void
    {
        $review = Reviews::find($reviewId);
        if ($review && $review->profile_id === $this->profile->id) {
            $review->delete();
            session()->flash('message', 'Review deleted successfully!');
        }
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        $reviews = Reviews::where('profile_id', $this->profile->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.biz-review', [
            'reviews' => $reviews
        ]);
    }
}
