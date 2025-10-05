<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BizCard+ | @yield('title')</title>
    <link rel="icon" href="#" type="image/x-icon">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['AvanteGarde', 'sans-serif'],
                    },
                    gridTemplateRows: {
                        auto: 'auto',
                    },
                    colors: {
                        'deep': '#224435',
                    },
                },
            },
        };
    </script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Mobile menu styles */
        #mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: white;
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }
        #mobile-menu.active {
            transform: translateX(0);
        }
        #mobile-menu ul li {
            padding: 1.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            width: 100%;
            text-align: center;
        }
        #mobile-menu ul li a {
            font-size: 1.25rem;
            color: #1f2937;
        }
        #close-menu {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            color: #1f2937;
        }
        .no-scroll {
            overflow: hidden;
        }
        /* Navbar background transition */
        #navbar {
            background-color: transparent;
            transition: all 0.3s ease-in-out;
        }
        #navbar.scrolled {
            background-color: white !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        /* Dropdown styles */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            min-width: 200px;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
        }
        .dropdown-menu.active {
            display: block;
        }
    </style>
</head>
<body>
<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 text-white py-8 px-4 md:px-14">
    <div class="flex flex-wrap items-center justify-between mx-auto">
        <div>
            <a href="/" class="flex items-center space-x-1 rtl:space-x-reverse">
                <span id="big1" class="self-center text-xl font-semibold whitespace-nowrap text-white">BizCard+</span>
            </a>
        </div>

        <!-- Desktop Menu -->
        <div class="" id="desktop-menu">
            <ul class="flex flex-col md:flex-row md:space-x-4 rtl:space-x-reverse md:mt-0 md:border-0 font-semibold font-sans items-center">
                @auth
                    <!-- Authenticated User Dropdown -->
                    <li class="relative">
                        <button id="user-dropdown-btn" class="flex items-center gap-2 py-2 px-3 rounded-lg hover:bg-white/10 transition duration-200 text-white">
                            <div class="w-8 h-8 rounded-full bg-[#F3763C] flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <i class='bx bx-chevron-down'></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown-menu" class="dropdown-menu">
                            <div class="py-2">
{{--                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">--}}
{{--                                    <i class='bx bx-home'></i>--}}
{{--                                    <span>Dashboard</span>--}}
{{--                                </a>--}}
{{--                                <a href="{{ route('settings.profile') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition duration-200">--}}
{{--                                    <i class='bx bx-cog'></i>--}}
{{--                                    <span>Settings</span>--}}
{{--                                </a>--}}
{{--                                <hr class="my-2">--}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition duration-200 w-full text-left">
                                        <i class='bx bx-log-out'></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @else
                    <!-- Guest Links -->
                    <li>
                        <a href="{{ route('login') }}" class="block py-2 px-3 md:hover:bg-transparent md:border-0 hover:text-yellow-400 md:p-0 text-lg text-white">Login</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="bg-[#F3763C] text-white px-6 py-3 rounded-lg hover:bg-[#e5692b] transition duration-200 flex items-center gap-2">
                            Get Started
                            <i class='bx bx-arrow-right'></i>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu" class="md:hidden">
    <button id="close-menu" class="text-3xl">
        <i class='bx bx-x'></i>
    </button>
    <ul class="flex flex-col items-center justify-center h-full">
        @auth
{{--            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>--}}
{{--            <li><a href="{{ route('settings.profile') }}">Settings</a></li>--}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600">Logout</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Get Started</a></li>
        @endauth
        <li><a href="/">How it works</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</div>

<!-- Mobile Menu Toggle -->
<div class="md:hidden fixed top-8 right-4 z-50">
    <button id="menu-toggle" class="text-white text-3xl">
        <i class='bx bx-menu'></i>
    </button>
</div>

<div class="">
    @yield('content')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</div>

<footer class="bg-gray-500">
    <!-- Your existing footer content -->
    <div class="grid md:grid-cols-2 grid-col-1 py-3 px-4 md:px-14 text-white pt-6">
        <div class="flex flex-col gap-4">
            <h1 class="text-3xl font-bold">BizCard+</h1>
            <p class="text-sm md:text-base">Drive your business to the next level.</p>
        </div>
        <div class="flex flex-col md:flex-row gap-12 md:ml-auto mt-12 md:mt-0">
            <div class="flex flex-col ">
                <h3 class="mb-4 font-medium">Quick Links</h3>
                <ul class="flex flex-col gap-2 text-white sm:mt-0 text-sm">
                    <li>
                        <a href="/" class="hover:underline">How it works</a>
                    </li>
                    <li>
                        <a href="/privacy" class="hover:underline">Privacy Policy</a>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col">
                <h3 class="mb-4 font-medium">Helpful Links</h3>
                <ul class="flex flex-col gap-2 text-white">
                    <li>
                        <a href="/about" class="hover:underline">About</a>
                    </li>
                    <li>
                        <a href="/contact" class="hover:underline">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col">
                <h3 class="mb-4 font-medium">Social</h3>
                <ul class="flex flex-col gap-2 text-white">
                    <li>
                        <a href="#" class="hover:underline">Instagram</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">Facebook</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">Tiktok</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">LinkedIn</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">X (Twitter)</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <hr class=" mx-4 md:mx-10 mt-6">

    <div class="flex justify-center items-center py-3">
        <p class="text-sm text-white">
            &copy; 2025 BizCard+. All rights reserved.
        </p>
    </div>
</footer>

<script>
    function handleImageUpload(event, targetInputId) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const el = document.getElementById(targetInputId);
            if (el) {
                el.value = e.target.result;
                el.dispatchEvent(new Event('input'));
            }
        };
        reader.readAsDataURL(file);
    }
</script>

<script>
    // Mobile menu functionality
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenu = document.getElementById('close-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const body = document.body;

    menuToggle.addEventListener('click', function() {
        mobileMenu.classList.add('active');
        body.classList.add('no-scroll');
    });

    closeMenu.addEventListener('click', function() {
        mobileMenu.classList.remove('active');
        body.classList.remove('no-scroll');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            body.classList.remove('no-scroll');
        });
    });

    // User dropdown functionality
    const dropdownBtn = document.getElementById('user-dropdown-btn');
    const dropdownMenu = document.getElementById('user-dropdown-menu');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('active');
            }
        });
    }

    // Scroll functionality
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        const navLogo = document.getElementById('big1');
        const navLinks = document.querySelectorAll('#desktop-menu a:not(button)');
        const userDropdownBtn = document.getElementById('user-dropdown-btn');

        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            navLogo.classList.remove('text-white');
            navLogo.classList.add('text-black');

            // Apply black text to desktop nav links
            navLinks.forEach(link => {
                link.classList.remove('text-white');
                link.classList.add('text-gray-900');
            });

            // Update dropdown button color
            if (userDropdownBtn) {
                userDropdownBtn.classList.remove('text-white');
                userDropdownBtn.classList.add('text-gray-900');
            }
        } else {
            navbar.classList.remove('scrolled');
            navLogo.classList.remove('text-black');
            navLogo.classList.add('text-white');

            // Revert to white text
            navLinks.forEach(link => {
                link.classList.remove('text-gray-900');
                link.classList.add('text-white');
            });

            // Revert dropdown button color
            if (userDropdownBtn) {
                userDropdownBtn.classList.remove('text-gray-900');
                userDropdownBtn.classList.add('text-white');
            }
        }
    });
</script>
</body>
</html>
