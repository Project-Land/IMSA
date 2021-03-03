<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izjava o primenjivosti')}} - {{__('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('statement-of-applicability.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>
	
	<div class="mx-auto md:w-full mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('statement-of-applicability.update', \Auth::user()->currentTeam->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')

            @foreach($fields as $field)
                <div class="flex border-b-2 py-2 my-2">
                    <div class="w-full sm:w-1/3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv kontrole')}}:</label>
                        <p>{{ $field->soa_fields }}</p>
                    </div>
                    <div class="w-full sm:w-1/3">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{__('Status kontrole')}}:</label>
                        <select class="text-xs sm:text-base mr-2 block border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="status[{{ $field->id }}]">
                            <option value="da">Prihvaćeno</option>
                            <option value="ne">Neprihvaćeno</option>
                            <option value="na">Nije primenljivo</option>
                        </select>
                        @error('status')
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="w-full sm:w-1/3">
                        <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">{{__('Komentar')}}:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="comment[{{ $field->id }}]" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">{{ $field->comment }}</textarea>
                        @error('comment')
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endforeach
			
			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Izmeni')}}</button>
		</form>
	</div>

</x-app-layout>
