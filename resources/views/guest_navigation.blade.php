<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">

    <!-- Guest Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 mt-2">
            <div class="flex items-center">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center pb-2">
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('images/logo.jpg') }}" alt="imsa-logo" class="w-15 h-15">
                    </a>
                </div>

            </div>

            <div class="flex justify-end">

                <!-- Menu -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">{{ __('Login') }}</a>
                    <a href="#" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">{{  __('Uputstvo za korišćenje') }}</a>
                    <a href="{{ route('about') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">{{ __('O aplikaciji') }}</a>
                    <a href="{{ route('contact') }}" class="text-sm text-gray-700 hover:text-gray-900 no-underline mx-2">{{ __('Kontakt') }}</a>
                </div>

                <div class="inline-flex items-center justify-end ml-40 md:ml-2">
                    <x-jet-dropdown width="48">

                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div><img style="max-width: 80%;" src="{{ asset('images/'.session('locale').'.png') }}" alt="lang"></div>
                                <div class="md:ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(session('locale') != "sr")
                                <x-jet-dropdown-link href="{{ route('lang', ['lang' => 'sr']) }}">
                                    <div class="inline-flex items-center justify-end mt-1">
                                        <div><img src="{{ asset('images/sr.png') }}" alt="sr"></div>
                                        <div class="ml-3">{{ __('Srpski') }}</div>
                                    </div>
                                </x-jet-dropdown-link>
                            @endif

                            @if(session('locale') != "en")
                                <x-jet-dropdown-link href="{{ route('lang', ['lang' => 'en']) }}">
                                    <div class="inline-flex items-center justify-end mt-1">
                                        <div><img src="{{ asset('images/en.png') }}" alt="en"></div>
                                        <div class="ml-3">{{ __('English') }}</div>
                                    </div>
                                </x-jet-dropdown-link>
                            @endif
                        </x-slot>

                    </x-jet-dropdown>
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

        <x-jet-responsive-nav-link href="#" :active="request()->routeIs('manual')">
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
