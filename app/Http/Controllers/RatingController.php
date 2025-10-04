<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();

        // Debug: Log the incoming request
        Log::info('Rating submission received', $request->all());

        // Validate the request - REMOVE customer_name since it's not in your migration
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Log::info('Validation passed', $validated);

        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('User not authenticated for rating submission');
            return back()->with('error', 'Please log in to submit a rating.');
        }

        Log::info('User authenticated', ['user_id' => Auth::id()]);

        // Check if user has already reviewed this business
        $existingReview = Reviews::where('profile_id', $validated['profile_id'])
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            Log::warning('Duplicate review attempt', [
                'profile_id' => $validated['profile_id'],
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'You have already reviewed this business.');
        }

        try {
            // Create the review - use user's name from auth, not from form
            $review = Reviews::create([
                'profile_id' => $validated['profile_id'],
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_approved' => false,
            ]);

            Log::info('Review created successfully', [
                'review_id' => $review->id,
                'profile_id' => $review->profile_id,
                'user_id' => $review->user_id,
                'rating' => $review->rating
            ]);

            return back()->with('success', 'Thank you for your rating! It will be visible after approval.');

        } catch (\Exception $e) {
            Log::error('Error creating review', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'profile_id' => $validated['profile_id'],
                'user_id' => Auth::id()
            ]);

            return back()->with('error', 'An error occurred while submitting your rating. Please try again.');
        }
    }
}
