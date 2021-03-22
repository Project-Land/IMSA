<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnik sa preispitivanja')}} - {{__('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('management-system-reviews.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad')}}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('management-system-reviews.update', $msr->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')

			<div class="mb-4">
				<label for="year" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Godina')}}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="year" id="year" required oninvalid="this.setCustomValidity('{{ __("Izaberite godinu") }}')" oninput="this.setCustomValidity('')" readonly>
					@foreach(range(2020, date('Y')+10) as $year)
						<option value="{{ $year }}" {{ $year == $msr->year ? "selected" : "" }}>{{ $year }}</option>
					@endforeach
				</select>
				@error('year')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="participants" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Učestvovali u preispitivanju')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="participants" name="participants" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->participants }}</textarea>
				@error('participants')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measures_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status mera iz prethodnog preispitivanja')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measures_status" name="measures_status" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->measures_status }}</textarea>
				@error('measures_status')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="internal_external_changes" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="internal_external_changes" name="internal_external_changes" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->internal_external_changes }}</textarea>
				@error('internal_external_changes')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="text-lg">{{ __('Performanse bezbednosti informacija')}}</p><hr>

			<div class="mb-4">
				<label for="monitoring_measurement_results" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rezultati praćenja i merenja')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="monitoring_measurement_results" name="monitoring_measurement_results" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->monitoring_measurement_results }}</textarea>
				@error('monitoring_measurement_results')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="checks_results_desc" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rezultati eksternih provera')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checks_results_desc" name="checks_results_desc" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->checks_results_desc }}</textarea>
				@error('checks_results_desc')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="relevant_communication_with_stakeholders" class="block text-gray-700 text-sm font-bold mb-2">{{__('Povratne informacije od zainteresovanih strana')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="relevant_communication_with_stakeholders" name="relevant_communication_with_stakeholders" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $msr->relevant_communication_with_stakeholders }}</textarea>
				@error('relevant_communication_with_stakeholders')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="text-lg">{{ __('Izlazne tačke preispitivanja')}}</p><hr>

			<div class="mb-4">
				<label for="improvement_opportunities" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Prilike za poboljšanja')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="improvement_opportunities" name="improvement_opportunities">{{ $msr->improvement_opportunities }}</textarea>
				<span class="text-xs text-gray-400 font-italic">{{ __('Polje nije obavezno')}}</span>
                @error('improvement_opportunities')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="needs_for_change" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Potrebe za izmenama u sistemu menadžmenta')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="needs_for_change" name="needs_for_change">{{ $msr->needs_for_change }}</textarea>
				<span class="text-xs text-gray-400 font-italic">{{ __('Polje nije obavezno')}}</span>
				@error('needs_for_change')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

            <div class="mb-4">
				<label for="needs_for_resources" class="block text-gray-700 text-sm font-bold mb-2">{{__('Potrebni resursi')}}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="needs_for_resources" name="needs_for_resources">{{ $msr->needs_for_resources }}</textarea>
				<span class="text-xs text-gray-400 font-italic">{{__('Polje nije obavezno')}}</span>
				@error('needs_for_resources')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Izmeni')}}</button>
		</form>
    </div>

</x-app-layout>
