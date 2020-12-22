<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Kreiranje obuke') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('trainings.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('trainings.store') }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Naziv obuke') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" autofocus required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis obuke') }}:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ old('description') }}</textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Vrsta obuke') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="type" id="type" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
					<option value="Interna">{{ __('Interna') }}</option>
					<option value="Eksterna">{{ __('Eksterna') }}</option>
				</select>
			</div>

			<div class="mb-4">
				<label for="num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih') }}:</label>
				<input type="number" min="1" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="num_of_employees" name="num_of_employees" value="{{ old('num_of_employees') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('num_of_employees')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="training_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Planirani termin') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="training_date" name="training_date" placeholder="xx.xx.xxxx xx:xx" value="{{ old('training_date') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" >
				@error('training_date')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="place" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mesto obuke') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="place" name="place" value="{{ old('place') }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('place')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="resources" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Resursi') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="resources" name="resources" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ old('resources') }}</textarea>
				@error('resources')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Realizovano') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="status" id="status">
					<option value="0">{{ __('Ne') }}</option>
					<option value="1">{{ __('Da') }}</option>
				</select>
			</div>

			<div class="mb-4" id="final_num_field" style="display: none">
				<label for="final_num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih') }} {{ __('realizovano') }}:</label>
				<input type="number" min="1" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="final_num_of_employees" name="final_num_of_employees" value="{{ old('final_num_of_employees') }}" oninvalid="this.setCustomValidity('{{ __("Izaberite broj zaposlenih") }}')" oninput="this.setCustomValidity('')">
			</div>

			<div class="mb-4" id="rating_field" style="display: none">
				<label for="rating" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ocena') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="rating" id="rating" oninvalid="this.setCustomValidity('{{ __("Izaberite ocenu") }}')" oninput="this.setCustomValidity('')">
					<option value="">{{ __('Izaberi') }}...</option>
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}">{{ $i }}</option>
					@endfor
				</select>
			</div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Kreiraj') }}</button>
		</form>
    </div>

</x-app-layout>


<style>
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	-webkit-appearance: none;
		margin: 0;
	}

	input[type=number] {
		-moz-appearance: textfield;
	}
</style>

<script>
	var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

   	$('#training_date').datetimepicker({
		format: 'd.m.Y H:i',
		minDate: 0,
		dayOfWeekStart: 1,
		scrollInput: false
	});

	$('#status').change( () => {
		if($('#status').val() == 1){
			$('#final_num_field').css('display', '');
			$('#rating_field').css('display', '');
			$('#rating').attr('required', true);
			$('#final_num_of_employees').attr('required', true);
		}
		else{
			$('#final_num_field').css('display', 'none');
			$('#rating_field').css('display', 'none');
			$('#final_num_of_employees').val('');
			$('#rating').val('');
			$('#rating').attr('required', false);
			$('#final_num_of_employees').attr('required', false);
		}
	})
</script>
