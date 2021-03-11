<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane')}} - {{__('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('stakeholders.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>


	<div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('stakeholders.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv / Ime')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="expectation" class="block text-gray-700 text-sm font-bold mb-2">{{__('Potrebe i očekivanja zainteresovane strane')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="expectation" name="expectation" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">{{ old('expectation') }}</textarea>
				@error('expectation')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="response" class="block text-gray-700 text-sm font-bold mb-2">{{__('Odgovor preduzeća na potrebe i očekivanja')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="response" name="response" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">{{ old('response') }}</textarea>
				@error('response')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
	</div>

</x-app-layout>
