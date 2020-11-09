<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodavanje novog korisnika') }}
        </h2>
    </x-slot>

    <div class="row mt-1">
        <div class="col">
            @if(Session::has('status'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ Session::get('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
        <div class="md:grid md:grid-cols-3 md:gap-6">

            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Kreiraj novog korisnika</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Kreirajte novi nalog za korisnike vaše aplikacije
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
               
                <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="name" value="{{ __('Ime') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
                            @error('name')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="username" value="{{ __('Korisničko ime') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" />
                            @error('username')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="email" value="{{ __('Email') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            @error('email')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                            <span class="text-xs text-gray-500">Polje nije obavezno</span>
                        </div>

                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="role" value="{{ __('Uloga') }}" class="block font-medium text-sm text-gray-700" />
                            <select class="block mt-1 appearance-none w-full border border-gray-700 font-small text-sm text-gray-700 py-3 px-2 pr-8 rounded-md shadow-sm focus:outline-none focus:bg-white focus:border-gray-500" name="role" id="role">
                                <option value="user" {{ old('role') == "user" ? "selected":"" }}>Korisnik</option>
                                <option value="editor" {{ old('role') == "editor" ? "selected":"" }}>Editor Internih Provera</option>
                                <option value="admin" {{ old('role') == "admin" ? "selected":"" }}>Administrator</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="password" value="{{ __('Lozinka') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            @error('password')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="password_confirmation" value="{{ __('Potvrdi lozinku') }}" class="block font-medium text-sm text-gray-700" />
                            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            @error('password_confirmation')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4 mb-4 mr-4">
                                {{ __('Kreiraj') }}
                            </x-jet-button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-jet-section-border />


    </div>

</x-app-layout>

