<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <!-- Guest Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 mt-2">
            <div class="flex">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center pb-2">
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('images/logo.jpg') }}" alt="imsa-logo" class="w-15 h-15 mt-2">
                    </a>
                </div>

            </div>

            <div class="flex justify-end">

                <!-- Menu -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">Login</a>
                    <a href="#" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">Uputstvo za korišćenje</a>
                    <a href="{{ route('about') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">O aplikaciji</a>
                    <a href="{{ route('contact') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">Kontakt</a>
                </div>

            </div>

            <!-- Hamburger Menu -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <x-jet-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
            {{ __('Login') }}
        </x-jet-responsive-nav-link>

        <x-jet-responsive-nav-link href="{{ route('manual') }}" :active="request()->routeIs('manual')">
            {{ __('Uputstvo za korišćenje') }}
        </x-jet-responsive-nav-link>

        <x-jet-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
            {{ __('O aplikaciji') }}
        </x-jet-responsive-nav-link>

        <x-jet-responsive-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
            {{ __('Kontakt') }}
        </x-jet-responsive-nav-link>

    </div>

</nav>
