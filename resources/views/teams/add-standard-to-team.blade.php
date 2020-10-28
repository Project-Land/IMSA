<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodavanje novog standarda firmi') }}
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
                    <h3 class="text-lg font-medium text-gray-900">Dodela standarda</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Dodelite novi standard firmi
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
               
                <form method="POST" action="/store-new-standard-to-team">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <input type="hidden" name="team_id" value="{{ request()->route('id') }}">
                        <div class="px-4 py-3 bg-white sm:p-6">
                            <x-jet-label for="role" value="{{ __('Standard') }}" class="block font-medium text-sm text-gray-700" />
                            <select class="block mt-1 appearance-none w-full border border-gray-700 font-small text-sm text-gray-700 py-3 px-2 pr-8 rounded-md shadow-sm focus:outline-none focus:bg-white focus:border-gray-500" name="standard" id="standard">
                                @foreach($standards as $standard)
                                    <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                                @endforeach
                            </select>
                            @error('standard')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4 mb-4 mr-4">
                                {{ __('Dodaj') }}
                            </x-jet-button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

        <x-jet-section-border />


    </div>

</x-app-layout>

