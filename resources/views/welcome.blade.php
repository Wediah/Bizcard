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
    </div>
@endsection
