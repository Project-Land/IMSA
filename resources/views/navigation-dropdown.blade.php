<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 mt-2">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                    <img src="/images/logo.png" class="w-20 h-20 mt-2">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex text-lg p-4">
                @inject('standards', 'standards')
                <x-jet-dropdown>
                    <x-slot name="trigger">
                    <a  type="button" class="btn trigger" style="@if(request()->is(['/','standards*'])) {{'border-bottom:3px solid gray'}} @endif" >Standardi</a>
                    
                    </x-slot> 
                    <div class="dropdown-menu"> 
                    <x-slot name="content">
                        @foreach($standards as $standard)
                        <a class="dropdown-item" href="{{'/standards/'.$standard->id}}">{{$standard->name}}</a>
                        @endforeach
                    </x-slot>
                    </div>
                    </x-jet-dropdown>
               
                    <x-jet-dropdown>
                    <x-slot name="trigger">
                    <button type="button" class="btn trigger" style="@if(request()->is(['rules-of-procedures*','manuals*','policies*','forms*','procedures*'])) {{'border-bottom:3px solid gray'}} @endif">Dokumentacija</button>
                    </x-slot> 
                    <div class="dropdown-menu">
                    <x-slot name="content">
                  
                    <a class="dropdown-item" href="/rules-of-procedures">Poslovnik</a>
                    <a class="dropdown-item" href="/policies">Politike</a>
                    <a class="dropdown-item" href="/procedures">Procedure</a>
                    <a class="dropdown-item" href="/manuals">Uputstva</a>
                    <a class="dropdown-item" href="/forms">Obrasci</a>          
                    </x-slot>
                    </div>
                    </x-jet-dropdown>


                    <x-jet-dropdown width="60">
                    <x-slot name="trigger">
                    <button type="button" class="btn trigger" style="@if(request()->is(['risk-management*','internal-check*','corrective-measures*','trainings*','goals*','suppliers*','stakeholders*','complaints*','management-system-reviews*'])) {{'border-bottom:3px solid gray'}} @endif">Sistemski procesi</button>
                    </x-slot> 
                    <div class="dropdown-menu">
                    <x-slot name="content">
                  
                    <a class="dropdown-item" href="/risk-management">Upravljanje rizikom</a>
                    <a class="dropdown-item" href="/internal-check">Interne provere</a>
                    <a class="dropdown-item" href="/corrective-measures">Neusaglašenosti i korektivne mere</a>
                    <a class="dropdown-item" href="/trainings">Obuke</a>
                    <a class="dropdown-item" href="/goals">Ciljevi</a>
                    <a class="dropdown-item" href="/suppliers">Odobreni isporučioci</a>
                    <a class="dropdown-item" href="/stakeholders">Zainteresovane strane</a>
                    <a class="dropdown-item" href="/complaints">Upravljanje reklamacijama</a>
                    <a class="dropdown-item" href="/management-system-reviews">Preispitivanje sistema menadžmenta</a>     
                    </x-slot>
                    </div>
                    </x-jet-dropdown>

                    <x-jet-dropdown>
                   
            <div class="dropdown-menu">
            <x-slot name="trigger">
            <button type="button" class="btn trigger" style="@if(request()->is(['sectors*'])) {{'border-bottom:3px solid gray'}} @endif">Sektori</button>
            </x-slot>
            <x-slot name="content">
                <a class="dropdown-item" href="/sectors">Lista sektora</a>
                @can('create', App\Models\Sector::class)  <a class="dropdown-item" href="/sectors/create">Dodaj sektor</a>  @endcan    
            </x-slot> 
           
            </div>
            </x-jet-dropdown>

                    
                </div>
            </div>

            <div class="flex justify-end">

                <!-- Notifications menu -->
                @inject('Notifications', 'Notifications')
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-jet-dropdown width="48">
                        <x-slot name="trigger">
                        @can('viewAny',App\Models\Notification::class)
                            <button class=" @if ($Notifications->count()) {{'bg-danger rounded p-2 text-white'}} @endif flex items-center text-md font-medium text-red-500 hover:text-red-700 hover:border-red-300 focus:outline-none focus:text-red-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{$Notifications->count()}} </div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        @endcan
                        </x-slot>

                        <x-slot name="content">
                      
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Obaveštenja') }}
                            </div>
                            @forelse($Notifications as $not)
                                @can('view',$not)

                                @if ($not->notifiable_type==='App\Models\InternalCheck')
                                <x-jet-dropdown-link href="{{'/internal-check#internalcheck'.$not->notifiable_id}}" >
                                    {{ __($not->message) }}
                                </x-jet-dropdown-link>
                                @elseif ($not->notifiable_type==='App\Models\Goal')
                                <x-jet-dropdown-link href="{{'/goals#goal'.$not->notifiable_id}}" >
                                    {{ __($not->message) }}
                                </x-jet-dropdown-link>
                                @else
                                <x-jet-dropdown-link href="{{'/suppliers#supplier'.$not->notifiable_id}}" >
                                    {{ __($not->message) }}
                                </x-jet-dropdown-link>
                                @endif
                                @endcan
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
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
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
                                {{ __('Trenutna firma') }}
                            </div>
                            <p class="block px-4 text-sm text-gray-800">{{ Auth::user()->currentTeam->name }}</p>
                            
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Upravljanje nalogom') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profil') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokeni') }}
                                </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>
                            
                            <!-- Team Management -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('update', $team))
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Upravljanje timovima') }}
                                </div>

                                <!-- Team Settings -->
                                <x-jet-dropdown-link href="{{ route('teams.index') }}">
                                    {{ __('Lista svih timova') }}
                                </x-jet-dropdown-link>

                                <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                    {{ __('Podešavanja tima') }}
                                </x-jet-dropdown-link>

                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                        {{ __('Kreiraj novi tim') }}
                                    </x-jet-dropdown-link>
                                @endcan

                                <div class="border-t border-gray-100"></div>
                            @endif

                            <!-- User Management -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Gate::check('update', $team))
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

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                    {{ __('Odjava') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>

            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
           
            <x-jet-dropdown>
            <x-slot name="trigger">
                    <a  type="button" class="btn trigger" style="@if(request()->is(['/','standards*'])) {{'border-bottom:3px solid gray'}} @endif" >Standardi</a>
                    
                    </x-slot> 
                    <div class="dropdown-menu"> 
                    <x-slot name="content">
                        @foreach($standards as $standard)
                        <a class="dropdown-item" href="{{'/standards/'.$standard->id}}">{{$standard->name}}</a>
                        @endforeach
                    </x-slot>
                    </div>
            </x-jet-dropdown>
        

            <x-jet-dropdown>
                    <x-slot name="trigger">
                    <button type="button" class="btn trigger">Dokumentacija</button>
                    </x-slot> 
                    <div class="dropdown-menu">
                    <x-slot name="content">
                  
                    <a class="dropdown-item" href="/rules-of-procedures">Poslovnik</a>
                    <a class="dropdown-item" href="/policies">Politike</a>
                    <a class="dropdown-item" href="/procedures">Procedure</a>
                    <a class="dropdown-item" href="/manuals">Uputstva</a>
                    <a class="dropdown-item" href="/forms">Obrasci</a>          
                    </x-slot>
                    </div>
                    </x-jet-dropdown>
           

            <x-jet-dropdown width="60">
                    <x-slot name="trigger">
                    <button type="button" class="btn trigger">Sistemski procesi</button>
                    </x-slot> 
                    <div class="dropdown-menu">
                    <x-slot name="content">
                  
                    <a class="dropdown-item" href="/risk-management">Upravljanje rizikom</a>
                    <a class="dropdown-item" href="/internal-check">Interne provere</a>
                    <a class="dropdown-item" href="/corrective-measures">Neusaglašenosti i korektivne mere</a>
                    <a class="dropdown-item" href="/trainings">Obuke</a>
                    <a class="dropdown-item" href="/goals">Ciljevi</a>
                    <a class="dropdown-item" href="/suppliers">Odobreni isporučioci</a>
                    <a class="dropdown-item" href="/stakeholders">Zainteresovane strane</a>
                    <a class="dropdown-item" href="/complaints">Upravljanje reklamacijama</a>
                    <a class="dropdown-item" href="/management-system-reviews">Preispitivanje sistema menadžmenta</a>     
                    </x-slot>
                    </div>
                    </x-jet-dropdown>


                    <x-jet-dropdown>
                   
                   <div class="dropdown-menu">
                   <x-slot name="trigger">
                   <button type="button" class="btn trigger">Sektori</button>
                   </x-slot>
                   <x-slot name="content">
                       <a class="dropdown-item" href="/sectors">Lista sektora</a>
                       @can('create', App\Models\Sector::class)  <a class="dropdown-item" href="/sectors/create">Dodaj sektor</a>  @endcan    
                   </x-slot> 
                  
                   </div>
                   </x-jet-dropdown>


           





        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profil') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokeni') }}
                    </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Odjava') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Upravljanje timovima') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Podešavanja tima') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                        {{ __('Kreiraj novi tim') }}
                    </x-jet-responsive-nav-link>

                    <div class="border-t border-gray-200"></div>

                    <!-- User management -->
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

            </div>
        </div>
    </div>
</nav>
