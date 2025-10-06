<div class="min-h-screen bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Apply theme colors dynamically -->
        <style>
            .theme-primary { color: {{ $profile->theme_colors['primary'] ?? '#3B82F6' }}; }
            .theme-primary-bg { background-color: {{ $profile->theme_colors['primary'] ?? '#3B82F6' }}; }
            .theme-secondary { color: {{ $profile->theme_colors['secondary'] ?? '#1E40AF' }}; }
            .theme-secondary-bg { background-color: {{ $profile->theme_colors['secondary'] ?? '#1E40AF' }}; }
            .theme-accent { color: {{ $profile->theme_colors['accent'] ?? '#F59E0B' }}; }
            .theme-accent-bg { background-color: {{ $profile->theme_colors['accent'] ?? '#F59E0B' }}; }

            /* Dynamic border colors */
            .theme-border-primary { border-color: {{ $profile->theme_colors['primary'] ?? '#3B82F6' }}; }
            .theme-border-accent { border-color: {{ $profile->theme_colors['accent'] ?? '#F59E0B' }}; }

            /* Hover effects */
            .theme-hover-primary:hover { color: {{ $profile->theme_colors['primary'] ?? '#3B82F6' }}; }
            .theme-hover-bg-primary:hover { background-color: {{ $profile->theme_colors['primary'] ?? '#3B82F6' }}; }
        </style>

        <div class="relative inline-block">
            <img src="{{ $profile->cover_image }}" class="h-full md:h-10/12 w-full object-cover" alt="landing page image"/>
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="absolute top-36 left-0 text-white py-4 pl-4 md:pl-14 md:w-1/2 z-10 space-y-3" data-aos="fade-up">
                <!-- Business Name with Theme Color -->
                <p class="text-4xl md:text-7xl font-bold theme-primary">
                    {{ $profile->business_name }}
                </p>

                <!-- Description -->
                <p class="text-xl md:text-2xl">{{ $profile->description }}</p>

                <div class="flex flex-row gap-2 text-sm md:text-md">
                    @if($profile->location)
                        <div class="flex flex-row items-center theme-accent">
                            <span class="mr-2">📍</span>
                            {{ $profile->location }}
                        </div>
                    @endif
                    @if($profile->phone)
                        <div class="flex items-center theme-accent">
                            <span class="mr-2">📞</span>
                            {{ $profile->phone }}
                        </div>
                    @endif
                </div>
                <div class="flex flex-row gap-2 text-sm md:text-md">
                    @foreach($profile->social_links as $platform => $link)
                        <a href="{{ $link }}" target="_blank" class="theme-hover-primary">
                            @if($platform === 'facebook')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            @elseif($platform === 'twitter')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            @elseif($platform === 'instagram')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            @elseif($platform === 'linkedin')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            @else
                                <span>🌐</span>
                            @endif
                        </a>

                    @endforeach
                </div>
            </div>
        </div>

        <div class="">
            <!-- Section Headers with Theme Colors -->
            <hr class="theme-border-primary my-8">
            <h1 class="text-lg uppercase theme-primary">How we serve</h1>
            <h2 class="items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3 theme-secondary">
                Delivering end-to-end solutions for all your needs.
            </h2>

            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    @foreach($services as $service)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition duration-200 hover:theme-border-primary">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-semibold text-gray-900 text-lg theme-primary">
                                    {{ $service->name }}
                                </h3>
                                @if($service->price)
                                    <span class="font-bold theme-accent">
                                        ${{ number_format($service->price, 2) }}
                                    </span>
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
                                <span class="text-xs px-2 py-1 theme-accent-bg text-white rounded-full">
                                    Available
                                </span>
                                <button class="text-sm font-medium theme-hover-primary transition duration-200">
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
            <!-- Reviews Section with Theme Colors -->
            <hr class="theme-border-primary my-8">
            <h1 class="text-lg uppercase theme-primary">Reviews</h1>
            <h2 class="items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3 theme-secondary">
                What our clients say about us.
            </h2>

            @if($approvedReviews->count() > 0)
                <div class="space-y-6">
                    @foreach($approvedReviews->take(5) as $review)
                        <div class="border-b border-gray-100 pb-6 mt-8">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <!-- User avatar with theme background -->
                                    <div class="w-10 h-10 theme-primary-bg rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold">
                                            {{ substr($review->customer_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 theme-primary">
                                            {{ $review->customer_name }}
                                        </h4>
                                        <div class="flex items-center">
                                            <!-- Star ratings with theme accent color -->
                                            <div class="flex theme-accent mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $review->rating ? 'theme-accent' : 'text-gray-300' }} text-sm">★</span>
                                                @endfor
                                            </div>
                                            <span class="text-gray-500 text-sm">{{ $review->created_at->format('M j, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                            @if($review->business_response)
                                <!-- Business response with theme secondary background -->
                                <div class="theme-secondary-bg bg-opacity-10 rounded-lg p-4 mt-3">
                                    <p class="text-sm font-semibold theme-secondary mb-1">
                                        Business Response
                                    </p>
                                    <p class="text-gray-600 text-sm">{{ $review->business_response }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-4 theme-accent">💬</div>
                    <p>No reviews yet. Be the first to review!</p>
                </div>
            @endif
        </div>

        <!-- Optional: Add a CTA button with theme colors -->
{{--        <div class="text-center mt-12">--}}
{{--            <button class="px-8 py-3 theme-primary-bg text-white rounded-lg hover:theme-secondary-bg transition duration-200 font-semibold">--}}
{{--                Contact Us Today--}}
{{--            </button>--}}
{{--        </div>--}}
    </div>
</div>
