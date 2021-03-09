<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ __('Sektori') }} - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="inline-flex items-center px-4 py-2 hover:no-underline bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="{{ route('sectors.index') }}"><i class="fas fa-arrow-left mr-1"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <form action="{{ route('sectors.store') }}" method="POST" class="bg-white mx-auto md:w-3/5 mt-2 md:mt-1 shadow-md rounded px-8 pt-6 pb-4 mb-4">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Naziv sektora') }}:</label>
            <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" autofocus required oninvalid="this.setCustomValidity('{{ __('Naziv nije popunjen') }}')"
            oninput="this.setCustomValidity('')">
            @error('name')
                <span class="text-red-700 italic text-sm mt-1">{{ __($message) }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end px-1 text-right sm:px-6">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">{{ __('Kreiraj') }}</button>
        </div>
    </form>

</x-app-layout>
