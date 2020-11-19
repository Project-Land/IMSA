<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnik sa preispitivanja - Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('management-system-reviews.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('management-system-reviews.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="year" class="block text-gray-700 text-sm font-bold mb-2">Godina:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="year" id="year" required oninvalid="this.setCustomValidity('Izaberite godinu')" oninput="this.setCustomValidity('')">
					@foreach(range(2019, date('Y')+10) as $year)
						<option value="{{ $year }}" {{ $year == old('year') ? "selected" : "" }}>{{ $year }}</option>
					@endforeach
				</select>
				@error('year')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="participants" class="block text-gray-700 text-sm font-bold mb-2">Učestvovali u preispitivanju:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="participants" name="participants" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('participants') }}</textarea>
				@error('participants')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measures_status" class="block text-gray-700 text-sm font-bold mb-2">Status mera iz prethodnog preispitivanja:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measures_status" name="measures_status" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('measures_status') }}</textarea>
				@error('measures_status')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="internal_external_changes" class="block text-gray-700 text-sm font-bold mb-2">Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="internal_external_changes" name="internal_external_changes" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('internal_external_changes') }}</textarea>
				@error('internal_external_changes')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="customer_satisfaction" class="block text-gray-700 text-sm font-bold mb-2">Potrebe i očekivanja zainteresovanih strana i obaveze za usklađenost:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="customer_satisfaction" name="customer_satisfaction" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('customer_satisfaction') }}</textarea>
				@error('customer_satisfaction')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="text-lg">Performanse životne sredine</p><hr>

			<div class="mb-4">
				<label for="monitoring_measurement_results" class="block text-gray-700 text-sm font-bold mb-2">Rezultati praćenja i merenja:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monitoring_measurement_results" name="monitoring_measurement_results" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('monitoring_measurement_results') }}</textarea>
				@error('monitoring_measurement_results')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="checks_results_desc" class="block text-gray-700 text-sm font-bold mb-2">Rezultati eksternih provera:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checks_results_desc" name="checks_results_desc" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('checks_results_desc') }}</textarea>
				@error('checks_results_desc')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="resource_adequacy" class="block text-gray-700 text-sm font-bold mb-2">Adekvatnost resursa:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="resource_adequacy" name="resource_adequacy" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('resource_adequacy') }}</textarea>
				@error('resource_adequacy')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="communication_and_objections" class="block text-gray-700 text-sm font-bold mb-2">Komunikacija i prigovori iz domena životne sredine:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="communication_and_objections" name="communication_and_objections" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')">{{ old('communication_and_objections') }}</textarea>
				@error('communication_and_objections')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="improvement_opportunities" class="block text-gray-700 text-sm font-bold mb-2">Prilike za poboljšanja:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="improvement_opportunities" name="improvement_opportunities">{{ old('improvement_opportunities') }}</textarea>
				@error('improvement_opportunities')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="text-lg">Izlazne tačke preispitivanja</p><hr>

			<div class="mb-4">
				<label for="cae" class="block text-gray-700 text-sm font-bold mb-2">Pogodnost, adekvatnost i efektivnost sistema menadžmenta životnom sredinom:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cae" name="cae">{{ old('cae ') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('cae')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="continous_improvement_opportunities" class="block text-gray-700 text-sm font-bold mb-2">Prilike za stalna poboljšanja:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="continous_improvement_opportunities" name="continous_improvement_opportunities">{{ old('continous_improvement_opportunities') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('continous_improvement_opportunities')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="needs_for_change" class="block text-gray-700 text-sm font-bold mb-2">Potrebe za izmenama u sistemu menadžmenta:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="needs_for_change" name="needs_for_change">{{ old('needs_for_change') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('needs_for_change')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measures_optional" class="block text-gray-700 text-sm font-bold mb-2">Mere, u slučaju da ciljevi životne sredine nisu ispunjeni:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measures_optional" name="measures_optional">{{ old('measures_optional') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('measures_optional')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="opportunities" class="block text-gray-700 text-sm font-bold mb-2">Prilike za poboljšanje i integrisanje sa drugim procesima i sistemima menadžmenta:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="opportunities" name="opportunities">{{ old('opportunities') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('opportunities')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="consequences" class="block text-gray-700 text-sm font-bold mb-2">Eventualne posledice po strateško usmerenje organizacije:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="consequences" name="consequences">{{ old('consequences') }}</textarea>
				<span class="text-xs text-gray-400 font-italic">Polje nije obavezno</span>
				@error('consequences')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
		</form>
    </div>

</x-app-layout>
