@extends('components.layouts.main')

@section('title', 'Main')

@section('content')
    <div class="relative">
        <img src="{{ asset('assets/land.jpg') }}" class="h-full md:h-10/12 w-full object-cover" alt="landing page image"/>
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="absolute md:top-96 top-36 left-0 text-white py-4 pl-4 md:pl-14 md:w-1/2 z-10 space-y-3" data-aos="fade-up">
            <p class="text-4xl md:text-9xl font-bold">BIzCard+</p>
            <p class="text-xl md:text-2xl">Scale to the next level with a new way of sharing <br> your small business with your community</p>
            <flux:button
                href="{{ route('register') }}"
                icon:trailing="arrow-up-right"
            >
                Get Started
            </flux:button>
        </div>

    </div>

    <div class="px-4 md:px-10 py-6 bg-white text-black md:w-2/3 pt-6">
        <h1 class="text-lg uppercase pt-3">Recent Activities</h1>

        @php
            $businesses = \App\Models\Profile::latest()->take(8)->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6">
            @foreach($businesses as $business)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200" data-aos="fade-up">
                    <a href="{{ route('business.show', ['slug' => $business->slug]) }}">
                    <!-- Cover Image -->
                    <div class="relative">
                        <img
                            src="{{ $business->cover_image }}"
                            alt="{{ $business->business_name }}"
                            class="w-full h-48 object-cover"
                        />
                    </div>

                    <!-- Business Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $business->business_name }}</h3>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm truncate">{{ $business->location }}</span>
                        </div>
                    </div>
                    </a>

                    <hr class="border-gray-300">

                    <div class="p-4">
                        <button
                            type="button"
                            onclick="openRateModal({{ $business->id }}, '{{ addslashes($business->business_name) }}')"
                            class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        >
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Rate us
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Custom Modal -->
    <div id="rateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <form id="ratingForm" method="POST" action="{{ route('ratings.store') }}">
                @csrf
                <input type="hidden" name="profile_id" id="formProfileId">
                <input type="hidden" name="rating" id="formRating" value="0" required>

                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900" id="modalBusinessName">Rate Business</h3>
                        <button type="button" onclick="closeRateModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <p class="text-gray-600">Your reviews go a long way.</p>

                        <!-- Star Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Your Rating *</label>
                            <div class="flex justify-center gap-1" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            onclick="setRating({{ $i }})"
                                            class="text-3xl text-gray-300 hover:text-yellow-400 transition duration-200 rating-star"
                                            data-rating="{{ $i }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <div id="ratingError" class="text-red-500 text-sm mt-2 text-center hidden">Please select a rating</div>
                        </div>

                        <!-- Comment Field -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review *</label>
                            <textarea
                                id="comment"
                                name="comment"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Share your experience..."
                                required
                            ></textarea>
                            @error('comment')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="closeRateModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                Submit Rating
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentBusinessId = null;
        let currentRating = 0;

        function openRateModal(businessId, businessName) {
            currentBusinessId = businessId;
            document.getElementById('modalBusinessName').textContent = `Rate ${businessName}`;
            document.getElementById('formProfileId').value = businessId;
            document.getElementById('rateModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            resetRatingForm();
        }

        function closeRateModal() {
            document.getElementById('rateModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetRatingForm();
        }

        function setRating(rating) {
            currentRating = rating;
            document.getElementById('formRating').value = rating;

            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });

            document.getElementById('ratingError').classList.add('hidden');
        }

        function resetRatingForm() {
            currentRating = 0;
            document.getElementById('formRating').value = 0;
            document.getElementById('comment').value = '';

            const stars = document.querySelectorAll('.rating-star');
            stars.forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });

            document.getElementById('ratingError').classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('ratingForm').addEventListener('submit', function(e) {
            if (currentRating === 0) {
                e.preventDefault();
                document.getElementById('ratingError').classList.remove('hidden');

                // Scroll to error
                document.getElementById('ratingError').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            // Basic comment validation
            const comment = document.getElementById('comment').value;
            if (!comment.trim()) {
                e.preventDefault();
                alert('Please enter your review comment.');
                return;
            }
        });

        // Close modal when clicking outside
        document.getElementById('rateModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRateModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRateModal();
            }
        });
    </script>

    <hr class="bg-black">
    <div class="px-4 md:px-10 py-6 bg-white text-black md:w-2/3">
        <h1 class="text-lg uppercase">Who we are</h1>
        <h2 class="items-center md:text-5xl text-3xl text-[#F3763C] font-bold py-4">
            BizCard+ empowers small businesses with beautiful digital presence, customer engagement, and growth analytics.
        </h2>
        <p class="text-md pb-4">
            With a user-centric approach, we meticulously design every aspect of your digital business card—delivering platforms that impress, function seamlessly, and drive real results. Whether it's crafting bespoke business profiles, executing pixel-perfect designs, navigating customer review systems, or providing actionable analytics, we transform your business vision into digital reality. From local bakeries to professional consultants, we ensure every feature aligns with your brand, customer needs, and growth ambitions.
        </p>
    </div>

    <hr class="bg-black">
    <div class="px-4 py-6 md:px-10 bg-amber-50 ">
        <h1 class="text-lg uppercase">How it works</h1>
        <h2 class="items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3">
            Build your digital presence <br> in minutes, not days.
        </h2>
        <p class="text-md pb-4 text-black">
            Get your business online with our simple four-step process:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 auto-rows-[minmax(150px,_auto)]">

            <div class="bento-box md:col-span-2 md:row-span-2 bg-amber-900 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.2)] border-2 border-amber-800">
                <div>
                    <h2 class="text-xl md:text-2xl font-semibold text-amber-50 mb-3">BizCard Platform</h2>
                    <p class="text-amber-100 text-sm md:text-base leading-relaxed">
                        A premier digital business card platform dedicated to helping small businesses establish their online presence quickly and professionally. With a commitment to simplicity, elegance, and client success. Your business growth is our priority.
                    </p>
                </div>
                <svg class="w-12 h-12 text-amber-400 self-end mt-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m0 0h-.75m.75 0v.75m0 0v-.75m0 0h.75M12 12.75h3.75m0 0h.75m-.75 0V15m0 0v-.75m0 0h.75M12 15.75h3.75m0 0h.75m-.75 0V18m0 0v-.75m0 0h.75" />
                </svg>
            </div>

            <div class="bento-box bg-amber-100 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.1)] border-2 border-amber-200">
                <div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">1. Create Account</h3>
                    <p class="text-amber-700 text-sm">Sign up in seconds with email or social login.</p>
                </div>
                <svg class="w-10 h-10 text-amber-600 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </div>

            <div class="bento-box bg-amber-500 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.2)] border-2 border-amber-400">
                <div>
                    <h3 class="text-lg font-semibold text-amber-50 mb-2">2. Setup Business</h3>
                    <p class="text-amber-100 text-sm">Add your services, photos, and contact information.</p>
                </div>
                <svg class="w-10 h-10 text-amber-900 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
            </div>

            <div class="bento-box md:col-span-2 bg-amber-100 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.1)] border-2 border-amber-200">
                <div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">3. Share Your Website</h3>
                    <p class="text-amber-700 text-sm">Get a custom link: yourbusiness.bizcard.com</p>
                </div>
                <svg class="w-10 h-10 text-amber-600 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                </svg>
            </div>

            <div class="bento-box md:col-span-2 bg-amber-500 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.2)] border-2 border-amber-400">
                <div>
                    <h3 class="text-lg font-semibold text-amber-50 mb-2">4. Receive & Review Feedback</h3>
                    <p class="text-amber-100 text-sm">Collect customer reviews and manage your reputation.</p>
                </div>
                <svg class="w-10 h-10 text-amber-900 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                </svg>
            </div>

            <div class="bento-box bg-amber-900 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.2)] border-2 border-amber-800">
                <div>
                    <h3 class="text-lg font-semibold text-amber-50 mb-2">5. Publish & Grow</h3>
                    <p class="text-amber-100 text-sm">Go live and watch your business visibility increase.</p>
                </div>
                <svg class="w-10 h-10 text-amber-400 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>

            <div class="bento-box bg-amber-100 p-6 rounded-xl shadow-sm flex flex-col justify-between transform transition duration-200 ease-in-out hover:scale-101 hover:shadow-[0_4px_15px_rgba(120,53,15,0.1)] border-2 border-amber-200">
                <div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">6. Track Analytics</h3>
                    <p class="text-amber-700 text-sm">Monitor visits, engagement, and customer interactions.</p>
                </div>
                <svg class="w-10 h-10 text-amber-600 self-end mt-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>

        </div>
    </div>


    <hr class="bg-black">
    <div class="px-4 md:px-10 py-6 bg-white text-black">
        <h1 class="text-lg uppercase">Success Stories</h1>
        <h2 class="items-center md:text-5xl text-3xl font-bold py-4 md:w-2/3">
            How BizCard transformed small businesses.
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md flex flex-col justify-between border-l-4 border-[#F3763C] transform transition duration-200 ease-in-out hover:scale-102 hover:shadow-lg">
                <div class="mb-6">
                    <span class="text-3xl text-gray-300 font-serif leading-none block mb-4">“</span>
                    <p class="text-base md:text-lg italic text-gray-700 leading-relaxed flex-grow">
                        BizCard helped my bakery get discovered online. I went from 5 to 50+ customers weekly just by sharing my digital card!
                    </p>
                    <span class="text-3xl text-gray-300 font-serif leading-none block mt-4 text-right">”</span>
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-[#224435] rounded-full mr-2"></div>
                        <p class="font-semibold text-gray-900">Maria Rodriguez</p>
                    </div>
                    <p class="text-sm text-gray-600">Sweet Treats Bakery</p>
                    <div class="flex text-[#F3763C] mt-1">
                        ★★★★★
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md flex flex-col justify-between border-l-4 border-[#224435] transform transition duration-200 ease-in-out hover:scale-102 hover:shadow-lg">
                <div class="mb-6">
                    <span class="text-3xl text-gray-300 font-serif leading-none block mb-4">“</span>
                    <p class="text-base md:text-lg italic text-gray-700 leading-relaxed flex-grow">
                        As a freelance photographer, BizCard gave me the professional online presence I needed. Client inquiries tripled in the first month!
                    </p>
                    <span class="text-3xl text-gray-300 font-serif leading-none block mt-4 text-right">”</span>
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-[#F3763C] rounded-full mr-2"></div>
                        <p class="font-semibold text-gray-900">James Chen</p>
                    </div>
                    <p class="text-sm text-gray-600">Chen Photography Studio</p>
                    <div class="flex text-[#F3763C] mt-1">
                        ★★★★★
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md flex flex-col justify-between border-l-4 border-[#F3763C] transform transition duration-200 ease-in-out hover:scale-102 hover:shadow-lg">
                <div class="mb-6">
                    <span class="text-3xl text-gray-300 font-serif leading-none block mb-4">“</span>
                    <p class="text-base md:text-lg italic text-gray-700 leading-relaxed flex-grow">
                        The review system built my credibility instantly. New customers trust my plumbing business because they can see real feedback!
                    </p>
                    <span class="text-3xl text-gray-300 font-serif leading-none block mt-4 text-right">”</span>
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-[#224435] rounded-full mr-2"></div>
                        <p class="font-semibold text-gray-900">Mike Thompson</p>
                    </div>
                    <p class="text-sm text-gray-600">Thompson Plumbing Services</p>
                    <div class="flex text-[#F3763C] mt-1">
                        ★★★★★
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md flex flex-col justify-between border-l-4 border-[#224435] transform transition duration-200 ease-in-out hover:scale-102 hover:shadow-lg">
                <div class="mb-6">
                    <span class="text-3xl text-gray-300 font-serif leading-none block mb-4">“</span>
                    <p class="text-base md:text-lg italic text-gray-700 leading-relaxed flex-grow">
                        Setting up my consulting business was so easy. The analytics helped me understand which services clients were most interested in.
                    </p>
                    <span class="text-3xl text-gray-300 font-serif leading-none block mt-4 text-right">”</span>
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-[#F3763C] rounded-full mr-2"></div>
                        <p class="font-semibold text-gray-900">Sarah Williams</p>
                    </div>
                    <p class="text-sm text-gray-600">Williams Business Consulting</p>
                    <div class="flex text-[#F3763C] mt-1">
                        ★★★★★
                    </div>
                </div>
            </div>

        </div>

        <!-- Stats Section -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-2xl md:text-3xl font-bold text-[#224435]">500+</div>
                <div class="text-sm text-gray-600 mt-2">Businesses Powered</div>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-2xl md:text-3xl font-bold text-[#F3763C]">10K+</div>
                <div class="text-sm text-gray-600 mt-2">Customer Reviews</div>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-2xl md:text-3xl font-bold text-[#224435]">98%</div>
                <div class="text-sm text-gray-600 mt-2">Satisfaction Rate</div>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="text-2xl md:text-3xl font-bold text-[#F3763C]">5min</div>
                <div class="text-sm text-gray-600 mt-2">Average Setup Time</div>
            </div>
        </div>

        @if (session('success'))
            <div class="fixed top-5 right-5 z-50 bg-green-500 text-white p-4 rounded-lg shadow-lg" id="alert-success">
                {{ session('success') }}
            </div>
            <script>
                // Auto-hide the success message after 5 seconds
                setTimeout(() => {
                    document.getElementById('alert-success').remove();
                }, 5000);
            </script>
        @endif

        @if (session('error'))
            <div class="fixed top-5 right-5 z-50 bg-red-500 text-white p-4 rounded-lg shadow-lg" id="alert-error">
                {{ session('error') }}
            </div>
            <script>
                // Auto-hide the error message after 5 seconds
                setTimeout(() => {
                    document.getElementById('alert-error').remove();
                }, 5000);
            </script>
        @endif
    </div>
@endsection
