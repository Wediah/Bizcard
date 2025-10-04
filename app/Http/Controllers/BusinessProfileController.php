<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Reviews;
use App\Models\Service;
use Illuminate\Http\Request;

class BusinessProfileController extends Controller
{
    public function show($slug)
    {
        // Load the published business by slug
        $profile = Profile::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Load services
        $services = Service::where('profile_id', $profile->id)->get();

        // Load only approved reviews for public view
        $approvedReviews = Reviews::where('profile_id', $profile->id)
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->get();

        return view('business-profile', compact('profile', 'services', 'approvedReviews'));
    }
}
