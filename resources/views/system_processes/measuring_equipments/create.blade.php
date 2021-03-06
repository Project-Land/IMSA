<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Merna oprema')}} - {{__('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2">
        	<a class="btn btn-light" href="{{ route('measuring-equipment.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
	</div>

	<div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">
		<form action="{{ route('measuring-equipment.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="label" class="block text-gray-700 text-sm font-bold mb-2">{{__('Oznaka merne opreme')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="label" name="label" value="{{ old('label') }}" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('label')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv merne opreme')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="last_calibration_date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum poslednjeg etaloniranja/baždarenja')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" name="last_calibration_date" id="last_calibration_date" value="{{ old('last_calibration_date') }}">
				@error('last_calibration_date')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="next_calibration_date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum sledećeg etaloniranja/baždarenja')}}</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="xx.xx.xxxx" name="next_calibration_date" id="next_calibration_date" value="{{ old('next_calibration_date') }}">
				@error('next_calibration_date')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
	</div>

</x-app-layout>

<script>
	var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

    $('#last_calibration_date').datetimepicker({
		timepicker: false,
		format:'d.m.Y',
        maxDate: 0,
		dayOfWeekStart: 1,
        scrollInput: false
	});

	$('#next_calibration_date').datetimepicker({
		timepicker: false,
		format:'d.m.Y',
        minDate: 0,
		dayOfWeekStart: 1,
        scrollInput: false
	});
</script>
