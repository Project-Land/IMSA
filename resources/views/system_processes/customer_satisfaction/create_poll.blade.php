<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zadovoljstvo korisnika')}} - {{__('Kreiranje ankete') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('customer-satisfaction.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>


	<div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('customer-satisfaction-poll.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

            @for($i = 1; $i <= 10; $i++)
                <div class="mb-4">
                    <label for="col{{ $i }}" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kolone')}} {{ $i }}:</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="col{{ $i }}" name="col{{ $i }}" value="{{ old('col'.$i) }}" @if($i == 1) autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')" @endif>
                    @error('col{{ $i }}')
                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endfor

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
	</div>

</x-app-layout>
