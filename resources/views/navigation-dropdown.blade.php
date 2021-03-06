<nav x-data="{ open: false, isOpen: false }" class="bg-white border-b border-gray-100">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 mt-2">
            <div class="flex items-center">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center pb-2">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('/storage/logos/'.\Auth::user()->currentTeam->logo) }}"
                            alt="{{ \Auth::user()->currentTeam->name }}" style="width:4.5rem; height:4.5rem;">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex text-lg p-4">

                    @inject('standards', 'standards')
                    <x-jet-dropdown>
                        <x-slot name="trigger">
                            <button type="button"
                                class="hover:no-underline hover:text-gray-700 text-base hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                style="@if(request()->is(['/','standards*'])) {{'border-bottom:3px solid gray'}} @endif">{{ __('Standardi') }}</a>
                        </x-slot>

                        <div class="dropdown-menu">
                            <x-slot name="content">
                                @foreach($standards as $standard)
                                <a class="hover:no-underline block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/standards/'.$standard->id) }}">{{$standard->name}}</a>
                                @endforeach
                            </x-slot>
                        </div>
                    </x-jet-dropdown>

                    <x-jet-dropdown>
                        <x-slot name="trigger">
                            <button type="button"
                                class="hover:text-gray-700 text-base hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                style="@if(request()->is(['rules-of-procedures*','manuals*','policies*','forms*','procedures*','external-documents*','other-internal-documents*'])) {{'border-bottom:3px solid gray; border-radius: 0;'}} @endif">{{ __('Dokumentacija') }}</button>
                        </x-slot>

                        <div class="dropdown-menu">
                            <x-slot name="content">
                                @empty(Session::get('standard'))
                                @else
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/rules-of-procedures') }}">{{ __('Poslovnik') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/policies') }}">{{ __('Politike') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/procedures') }}">{{ __('Procedure') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/manuals') }}">{{ __('Uputstva') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/forms') }}">{{ __('Obrasci') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/external-documents') }}">{{ __('Eksterna dokumenta') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/other-internal-documents') }}">{{ __('Ostala interna dokumenta') }}</a>
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/certification-documents') }}">{{ __('Sertifikati') }}</a>
                                @endempty
                            </x-slot>
                        </div>
                    </x-jet-dropdown>

                    @inject('system_processes', 'system_processes')
                    <x-jet-dropdown>
                        <x-slot name="trigger">
                            <button type="button"
                                class="hover:text-gray-700 text-base hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                style="@if(request()->is(['risk-management*','internal-check*','corrective-measures*','trainings*','goals*','suppliers*','stakeholders*','complaints*','measuring-equipment*','evaluation-of-requirements*','environmental-aspects*','statement-of-applicability*','accidents*','customer-satisfaction*','management-system-reviews*'])) {{'border-bottom:3px solid gray; border-radius: 0;'}} @endif">{{ __('Sistemski procesi') }}</button>
                        </x-slot>

                        <div class="dropdown-menu">
                            <x-slot name="content">
                                @empty(Session::get('standard'))
                                @else
                                @foreach($system_processes as $sp)
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset($sp->route) }}">{{ __($sp->name) }}</a>
                                @endforeach
                                @endempty
                            </x-slot>
                        </div>
                    </x-jet-dropdown>

                    <x-jet-dropdown>
                        <div class="dropdown-menu">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="hover:text-gray-700 text-base hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                    style="@if(request()->is(['sectors*'])) {{'border-bottom:3px solid gray; border-radius: 0;'}} @endif">{{ __('Sektori') }}</button>
                            </x-slot>

                            <x-slot name="content">
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/sectors') }}">{{ __('Lista sektora') }}</a>
                                @can('create', App\Models\Sector::class)
                                <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    href="{{ asset('/sectors/create') }}">{{ __('Dodaj sektor') }}</a>
                                @endcan
                            </x-slot>
                        </div>
                    </x-jet-dropdown>

                </div>

            </div>

            <div class="flex justify-end">

                <div class="inline-flex items-center justify-end ml-10">
                    <x-jet-dropdown width="48">

                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div><img style="max-width: 80%;" src="{{ asset('images/'.session('locale').'.png') }}"
                                        alt="lang"></div>
                                <div class="md:ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(session('locale') != "sr")
                            <x-jet-dropdown-link href="{{ route('lang', ['lang'=>'sr']) }}">
                                <div class="inline-flex items-center justify-end mt-1">
                                    <div><img src="{{ asset('images/sr.png') }}" alt="sr"></div>
                                    <div class="ml-3">{{ 'Srpski' }}</div>
                                </div>
                            </x-jet-dropdown-link>
                            @endif

                            @if(session('locale') != "en")
                            <x-jet-dropdown-link href="{{ route('lang', ['lang'=>'en']) }}">
                                <div class="inline-flex items-center justify-end mt-1">
                                    <div><img src="{{ asset('images/en.png') }}" alt="en"></div>
                                    <div class="ml-3">{{ 'English' }}</div>
                                </div>
                            </x-jet-dropdown-link>
                            @endif

                            @if(session('locale') != "hr")
                            <x-jet-dropdown-link href="{{ route('lang', ['lang'=>'hr']) }}">
                                <div class="inline-flex items-center justify-end mt-1">
                                    <div><img src="{{ asset('images/hr.png') }}" alt="hr"></div>
                                    <div class="ml-3">{{ 'Hrvatski' }}</div>
                                </div>
                            </x-jet-dropdown-link>
                            @endif

                            @if(session('locale') != "it")
                            <x-jet-dropdown-link href="{{ route('lang', ['lang'=>'it']) }}">
                                <div class="inline-flex items-center justify-end mt-1">
                                    <div><img src="{{ asset('images/it.png') }}" alt="it"></div>
                                    <div class="ml-3">{{ 'Italiano' }}</div>
                                </div>
                            </x-jet-dropdown-link>
                            @endif
                        </x-slot>

                    </x-jet-dropdown>
                </div>

                <!-- Notifications menu -->
                @inject('Notifications', 'Notifications')

                @inject('InstantNotifications', 'InstantNotifications')

                @inject('CountInstantNotifications', 'CountInstantNotifications')

                <div class="inline-flex items-center justify-end ml-4">
                    <x-jet-dropdown class="w-56">

                        <x-slot name="trigger">
                            {{-- @can('viewAny', App\Models\Notification::class) --}}
                            {{-- @dd($InstantNotifications) --}}
                            <button
                                class=" {{ $Notifications->count() + $CountInstantNotifications ? 'bg-red-700 rounded-md p-2 text-xs md:text-sm text-white' : '' }} flex items-center text-sm sm:text-md font-medium text-red-500 hover:text-red-700 hover:border-red-300 focus:outline-none focus:text-red-700 focus:border-red-300 transition duration-150 ease-in-out">
                                <div>{{ $Notifications->count() + $CountInstantNotifications }}</div>
                                <div class="md:ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            {{-- @endcan --}}
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Obaveštenja') }}
                            </div>

                            @forelse($Notifications as $not)
                            {{-- @can('view', $not) --}}
                            @if ($not->notifiable_type === 'App\Models\InternalCheck')
                            <x-jet-dropdown-link
                                href="{{ asset('/internal-check#internalcheck'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @elseif ($not->notifiable_type === 'App\Models\Goal')
                            <x-jet-dropdown-link href="{{ asset('/goals#goal'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @elseif ($not->notifiable_type === 'App\Models\Complaint')
                            <x-jet-dropdown-link href="{{ asset('/complaints#complaint'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @elseif ($not->notifiable_type === 'App\Models\CorrectiveMeasure')
                            <x-jet-dropdown-link
                                href="{{ asset('/corrective-measures#correctivemeasure'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @elseif ($not->notifiable_type === 'App\Models\MeasuringEquipment')
                            <x-jet-dropdown-link
                                href="{{ asset('/measuring-equipment#measuringequipment'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @else
                            <x-jet-dropdown-link href="{{ asset('/suppliers#supplier'.$not->notifiable_id) }}">
                                {{ __($not->message) }}
                            </x-jet-dropdown-link>
                            @endif
                            {{-- @endcan --}}
                            @empty
                            @endforelse

                            @forelse($InstantNotifications as $inst)
                            <div class="{{ $inst->pivot->is_read == 0 ? 'bg-red-100':'' }}">
                                <x-jet-dropdown-link href="{{ $inst->data }}">
                                    {{ __($inst->message) }}
                                </x-jet-dropdown-link>
                                <div class="flex justify-end px-2 py-2 leading-5 text-xs">
                                    {{-- <p class="px-2">{{ date('d.m.Y H:i', strtotime($inst->pivot->created_at)) }}
                                    </p> --}}
                                    @if($inst->pivot->is_read == 0)
                                    <a href="{{ route('mark-as-read', $inst->id) }}" data-toggle="tooltip"
                                        data-placement="top" title="{{ __('Označi kao pročitano') }}"
                                        class="block px-2 text-gray-700 hover:text-green-500 hover:no-underline focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"><i
                                            class="fas fa-check"></i></a>
                                    @endif
                                    <a href="{{ route('delete-notification', $inst->id) }}" data-toggle="tooltip"
                                        data-placement="top" title="{{ __('Obriši obaveštenje') }}"
                                        class="block px-2 text-gray-700 hover:text-red-500 hover:no-underline focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"><i
                                            class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <hr class="py-0 my-0">
                            @empty
                            @endforelse
                        </x-slot>

                    </x-jet-dropdown>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">

                    <x-jet-dropdown align="right" width="48">

                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                <img class="h-8 w-8 rounded-full object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            @else
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            @endif
                        </x-slot>

                        @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" />
                        @endforeach

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Firma') }}
                            </div>
                            <p class="block px-4 text-sm text-gray-800">{{ Auth::user()->currentTeam->name }}</p>

                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Upravljanje nalogom') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profil') }}
                            </x-jet-dropdown-link>

                            @if(Gate::check('userManagement', $team))
                            <x-jet-dropdown-link href="{{ route('users.notification-settings') }}">
                                {{ __('Podešavanje email notifikacija') }}
                            </x-jet-dropdown-link>
                            @endif

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                {{ __('API Tokeni') }}
                            </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Team Management -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('update', $team))
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Upravljanje firmama') }}
                            </div>

                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <!-- Team Settings -->
                            <x-jet-dropdown-link href="{{ route('teams.index') }}">
                                {{ __('Lista svih firmi') }}
                            </x-jet-dropdown-link>
                            @endcan

                            <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                {{ __('Podešavanja firme') }}
                            </x-jet-dropdown-link>

                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                {{ __('Kreiraj novu firmu') }}
                            </x-jet-dropdown-link>
                            @endcan
                            @endif

                            <!-- User Management -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('userManagement', $team))
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Upravljanje korisnicima') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('users.index') }}">
                                {{ __('Lista korisnika') }}
                            </x-jet-dropdown-link>

                            <x-jet-dropdown-link href="{{ route('users.create') }}">
                                {{ __('Kreiraj novog korisnika') }}
                            </x-jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>
                            @endif

                            <!-- System processes -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() &&
                            Gate::check('SystemProcessesManagement', $team))
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Sistemski procesi') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('system-processes.add-to-standard') }}">
                                {{ __('Dodavanje procesa za standard') }}
                            </x-jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                    {{ __('Odjava') }}
                                </x-jet-dropdown-link>
                            </form>

                        </x-slot>

                    </x-jet-dropdown>

                </div>

            </div>

            <!-- Hamburger Menu -->
            <div class="flex items-center sm:hidden">
                <button @click="isOpen = ! isOpen; open==true ? open = ! open : ''"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': isOpen, 'inline-flex': ! isOpen }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! isOpen, 'inline-flex': isOpen }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Hamburger Profile & Settings -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open; isOpen==true ? isOpen = ! isOpen : ''"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Menus -->
    <div :class="{'block': isOpen, 'hidden': ! isOpen}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <div class="border-t border-gray-200"></div>

            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ __('Meni') }}
            </div>

            <div @click.away="open = false" class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex flex-row items-center w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <span>{{ __('Standardi') }}</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 right-0 w-full mt-2 origin-top-right rounded-md shadow-lg md:w-48">
                    <div class="bg-white shadow -mt-1">
                        @foreach($standards as $standard)
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/standards/'.$standard->id) }}">{{ $standard->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div @click.away="open = false" class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex flex-row items-center w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <span>{{ __('Dokumentacija') }}</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 right-0 w-full mt-2 origin-top-right rounded-md shadow-lg md:w-48">
                    <div class="bg-white shadow -mt-1">
                        @empty(Session::get('standard'))

                        @else
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/rules-of-procedures') }}">{{ __('Poslovnik') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/policies') }}">{{ __('Politike') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/procedures') }}">{{ __('Procedure') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/manuals') }}">{{ __('Uputstva') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/forms') }}">{{ __('Obrasci') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/external-documents') }}">{{ __('Eksterna dokumenta') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/other-internal-documents') }}">{{ __('Ostala interna dokumenta') }}</a>
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/certification-documents') }}">{{ __('Sertifikati') }}</a>
                        @endempty
                    </div>
                </div>
            </div>

            <div @click.away="open = false" class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex flex-row items-center w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <span>{{ __('Sistemski procesi') }}</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 right-0 w-full mt-2 origin-top-right rounded-md shadow-lg md:w-48">
                    <div class="bg-white shadow -mt-1">
                        @empty(Session::get('standard'))

                        @else
                        @foreach($system_processes as $sp)
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset($sp->route) }}">{{ __($sp->name) }}</a>
                        @endforeach
                        @endempty
                    </div>
                </div>
            </div>

            <div @click.away="open = false" class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex flex-row items-center w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    <span>{{ __('Sektori') }}</span>
                    <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 right-0 w-full mt-2 origin-top-right rounded-md shadow-lg md:w-48">
                    <div class="bg-white shadow -mt-1">
                        <a class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/sectors') }}">{{ __('Lista sektora') }}</a>
                        @can('create', App\Models\Sector::class) <a
                            class="block px-4 py-2 text-sm leading-5 hover:no-underline text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            href="{{ asset('/sectors/create') }}">{{ __('Dodaj sektor') }}</a> @endcan
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Responsive Profile & Settings -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" />
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">

                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" class="hover:no-underline"
                    :active="request()->routeIs('profile.show')">
                    {{ __('Profil') }}
                </x-jet-responsive-nav-link>

                @if(Gate::check('userManagement', $team))
                <x-jet-responsive-nav-link href="{{ route('users.notification-settings') }}" class="hover:no-underline"
                    :active="request()->routeIs('users.notification-settings')">
                    {{ __('Podešavanje email notifikacija') }}
                </x-jet-responsive-nav-link>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" class="hover:no-underline"
                    :active="request()->routeIs('api-tokens.index')">
                    {{ __('API Tokeni') }}
                </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-jet-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                        this.closest('form').submit();">
                        {{ __('Odjava') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('update', $team))
                <div class="border-t border-gray-200"></div>

                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Upravljanje firmama') }}
                </div>

                <x-jet-responsive-nav-link href="{{ route('teams.index') }}" class="hover:no-underline"
                    :active="request()->routeIs('teams.index')">
                    {{ __('Lista svih firmi') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                    class="hover:no-underline" :active="request()->routeIs('teams.show')">
                    {{ __('Podešavanja firme') }}
                </x-jet-responsive-nav-link>

                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                <x-jet-responsive-nav-link href="{{ route('teams.create') }}" class="hover:no-underline"
                    :active="request()->routeIs('teams.create')">
                    {{ __('Kreiraj novu firmu') }}
                </x-jet-responsive-nav-link>
                @endcan

                <div class="border-t border-gray-200"></div>
                @endif

                <!-- User management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('userManagement', $team))
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Upravljanje korisnicima') }}
                </div>

                <x-jet-responsive-nav-link href="{{ route('users.index') }}" class="hover:no-underline"
                    :active="request()->routeIs('users.index')">
                    {{ __('Lista korisnika') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('users.create') }}" class="hover:no-underline"
                    :active="request()->routeIs('users.create')">
                    {{ __('Kreiraj novog korisnika') }}
                </x-jet-responsive-nav-link>

                <div class="border-t border-gray-100"></div>
                @endif

                <!-- System processes -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('SystemProcessesManagement', $team))
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Sistemski procesi') }}
                </div>

                <x-jet-responsive-nav-link href="{{ route('system-processes.add-to-standard') }}"
                    class="hover:no-underline" :active="request()->routeIs('system-processes.add-to-standard')">
                    {{ __('Dodavanje procesa za standard') }}
                </x-jet-responsive-nav-link>

                <div class="border-t border-gray-100"></div>
                @endif

                <!-- Server management -->
                {{-- @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('serverInfo', $team))
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Server info') }}
            </div>

            <x-jet-responsive-nav-link href="{{ route('analytics') }}" class="hover:no-underline">
                {{ __('Server log') }}
            </x-jet-responsive-nav-link>

            <div class="border-t border-gray-100"></div>
            @endif --}}

        </div>
    </div>
    </div>

</nav>

<script>
    let element = document.getElementsByClassName('w-48');
    element[5].style.overflow="auto";
    element[5].style.maxHeight="90vh";
    element[5].classList.add('w-64');
    element[5].classList.add('md:w-80');

    $('[data-toggle="tooltip"]').tooltip();
</script>
