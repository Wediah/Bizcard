@extends('components.layouts.main')
<div>
    <div class="relative inlines-block">
        <img src="{{ $profile->cover_image }}" class="h-full md:h-10/12 w-full object-cover" alt="landing page image"/>
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute top-36 left-0 text-white py-4 pl-4 md:pl-14 md:w-1/2 z-10 space-y-3" data-aos="fade-up">
            <p class="text-4xl md:text-7xl font-bold">{{ $profile->business_name }}</p>
            <p class="text-xl md:text-2xl">{{ $profile->description }}</p>
            <div class="flex flex-row gap-2 text-sm md:text-md">
                @if($profile->location)
                    <div class="flex flex-row items-center">
                        <span class="mr-2">📍</span>
                        {{ $profile->location }}
                    </div>
                @endif
                @if($profile->phone)
                    <div class="flex items-center">
                        <span class="mr-2">📞</span>
                        {{ $profile->phone }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="">
        <hr class="bg-black my-8">
        <h1 class="text-lg uppercase">How we serve</h1>
        <h2 class=" items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3">
            Delivering end-to-end solutions for all your needs.
        </h2>

        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                @foreach($services as $service)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $service->name }}</h3>
                            @if($service->price)
                                <span class="font-bold text-gray-900">${{ number_format($service->price, 2) }}</span>
                            @endif
                        </div>
                        @if($service->description)
                            <p class="text-gray-600 text-sm mb-4">{{ $service->description }}</p>
                        @endif
                        @if($service->image)
                            <img src="{{ $service->image }}" alt="{{ $service->name }}"
                                 class="w-full h-32 object-cover rounded-lg mb-3">
                        @endif
                        <div class="flex justify-between items-center">
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                        Available
                                    </span>
                            <button class="text-red-600 text-sm font-medium hover:text-red-800">
                                Learn More
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-4">🔧</div>
                <p>No services listed yet.</p>
            </div>
        @endif
    </div>

    <div>
        <hr class="bg-black my-8">
        <h1 class="text-lg uppercase">Reviews</h1>
        <h2 class=" items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3">
            What our clients say about us.
        </h2>

        @if($approvedReviews->count() > 0)
            <div class="space-y-6">
                @foreach($approvedReviews->take(5) as $review)
                    <div class="border-b border-gray-100 pb-6 mt-8">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-gray-600 font-semibold">{{ substr($review->customer_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $review->customer_name }}</h4>
                                    <div class="flex items-center">
                                        <div class="flex text-red-500 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? 'text-red-500' : 'text-gray-300' }} text-sm">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-gray-500 text-sm">{{ $review->created_at->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                        @if($review->business_response)
                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                <p class="text-sm font-semibold text-gray-900 mb-1">Business Response</p>
                                <p class="text-gray-600 text-sm">{{ $review->business_response }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-4">💬</div>
                <p>No reviews yet. Be the first to review!</p>
            </div>
        @endif
    </div>

