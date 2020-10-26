<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista korisnika') }}
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

    <x-jet-validation-errors class="mb-4" />

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
        <div class="md:grid md:grid-cols-3 md:gap-6">

            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Lista korisnika</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Lista svih korisnika aplikacije za firmu {{ $users->first()->currentTeam->name }}
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
               
                <div class="shadow overflow-hidden sm:rounded-md">
                    @foreach($users as $user)
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }}</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="ml-2 text-sm text-gray-400">{{ $user->allTeams()->first()->membership->role }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <x-jet-section-border />

    </div>

</x-app-layout>

