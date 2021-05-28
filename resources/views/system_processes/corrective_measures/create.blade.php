<x-app-layout>
    <x-slot name="header">
	<div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Neusaglašenosti i korektivne mere') }} - {{ __('Kreiranje') }}
        </h2>
		@include('includes.video')
    </div>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('corrective-measures.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" autocomplete="off" action="{{ route('corrective-measures.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="standard_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Sistem menadžment') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="standard_id" id="standard_id" disabled required oninvalid="this.setCustomValidity('{{ __("Izaberite sistem menadžmenta") }}')"
                oninput="this.setCustomValidity('')">
					<option value="">{{ __('Izaberi') }}...</option>
					@foreach($standards as $standard)
						<option value="{{ $standard->id }}" {{ $standard->id == Session::get('standard')? "selected":"" }}>{{ $standard->name }}</option>
					@endforeach
				</select>
				@error('standard_id')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="noncompliance_source" id="noncompliance_source"
				required oninvalid="this.setCustomValidity('{{ __("Izvor informacije o neusaglašenostima nije izabran") }}')"
                oninput="this.setCustomValidity('')">
					<option value="">{{ __('Izaberi') }}...</option>
					<option value="Eksterna provera" {{ old('noncompliance_source') == "Eksterna provera" ? "selected" : "" }}>{{ __('Eksterna provera') }}</option>
					<option value="Interna provera" {{ old('noncompliance_source') == "Interna provera" ? "selected" : "" }}>{{ __('Interna provera') }}</option>
					<option value="Preispitivanje ISM-a" {{ old('noncompliance_source') == "Preispitivanje ISM-a" ? "selected" : "" }}>{{ __('Preispitivanje ISM-a') }}</option>
					<option value="Žalba" {{ old('noncompliance_source') == "Žalba" ? "selected" : "" }}>{{ __('Žalba') }}</option>
					<option value="Ostalo" {{ old('noncompliance_source') == "Ostalo" ? "selected" : "" }}>{{ __('Ostalo') }}</option>
				</select>
				@error('noncompliance_source')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Organizaciona celina u kojoj je utvrđena neusaglašenost') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="sector_id" id="sector_id" required oninvalid="this.setCustomValidity('{{ __("Organizaciona celina u kojoj je utvrđena neusaglašenost nije izabrana") }}')"
                oninput="this.setCustomValidity('')">
					<option value="">{{ __('Izaberi') }}...</option>
					@foreach($sectors as $sector)
						<option value="{{ $sector->id }}">{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector_id')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('{{ __("Opis neusaglašenosti nije popunjen") }}')"
                oninput="this.setCustomValidity('')">{{ old('noncompliance_description') }}</textarea>
				@error('noncompliance_description')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('{{ __("Uzrok neusaglašenosti nije popunjen") }}')"
                oninput="this.setCustomValidity('')">{{ old('noncompliance_cause') }}</textarea>
				@error('noncompliance_cause')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('{{ __("Mera za otklanjanje neusaglašenosti nije popunjena") }}')"
                oninput="this.setCustomValidity('')">{{ old('measure') }}</textarea>
				@error('measure')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje korektivne mere') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
					<option value="1" {{ old('measure_approval') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
					<option value="0" {{ old('measure_approval') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
				</select>
            </div>

            <div class="mb-4">
                <label for="deadline_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju korektivne mere') }}:</label>
                <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline_date" name="deadline_date" value="{{ old('deadline_date') }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
				@error('deadline_date')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
            </div>

			<div class="mb-4" id="measure_reason_field" style="display: none">
				<label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Razlog neodobravanja mere') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="measure_approval_reason" id="measure_approval_reason" >
				@error('measure_approval_reason')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je mera sprovedena?') }}</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
					<option value="0" {{ old('measure_status') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
					<option value="1" {{ old('measure_status') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
				</select>
			</div>

			<div class="mb-4" id="measure_effective_field" style="display: none">
				<label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
					<option value="">{{ __('Izaberi') }}...</option>
					<option value="1" {{ old('measure_effective') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
					<option value="0" {{ old('measure_effective') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
				</select>
			</div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Kreiraj') }}</button>
		</form>
    </div>

</x-app-layout>

<script>

    var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

	$('#deadline_date').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
		minDate: 0,
		dayOfWeekStart: 1,
    	scrollInput: false
	});

	$('#measure_approval').change( () => {
		if($('#measure_approval').val() == 0){
			$('#measure_reason_field').css('display', '');
		}
		else{
			$('#measure_reason_field').css('display', 'none');
			$('#measure_reason').val('');
		}
	})

	$('#measure_status').change( () => {
		if($('#measure_status').val() == 1){
			$('#measure_effective_field').css('display', '');
		}
		else{
			$('#measure_effective_field').css('display', 'none');
		}
	})

</script>
