<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izmena obuke') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('trainings.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('trainings.update', $trainingPlan->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')

            <div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Naziv obuke') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ $trainingPlan->name }}" autofocus required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis obuke') }}:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $trainingPlan->description  }}</textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="type" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Vrsta obuke') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="type" id="type">
					<option value="Interna" {{ $trainingPlan->type == "Interna" ? "selected": "" }} >{{ __('Interna') }}</option>
					<option value="Eksterna" {{ $trainingPlan->type == "Eksterna" ? "selected": "" }} >{{ __('Eksterna') }}</option>
				</select>
			</div>

			<div class="mb-4">
				<label for="num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih') }}:</label>
				<input type="number" min="1" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="num_of_employees" name="num_of_employees" value="{{ $trainingPlan->num_of_employees  }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('num_of_employees')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="training_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Planirani termin') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="training_date" name="training_date" placeholder="xx.xx.xxxx xx:xx" value="{{ date('d.m.Y H:i', strtotime($trainingPlan->training_date))  }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')">
				@error('training_date')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="place" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mesto obuke') }}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="place" name="place" value="{{ $trainingPlan->place  }}" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">
				@error('place')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="resources" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Resursi') }}:</label>
				<textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="resources" name="resources" required oninvalid="this.setCustomValidity('{{ __("Popunite polje") }}')" oninput="this.setCustomValidity('')">{{ $trainingPlan->resources  }}</textarea>
				@error('resources')
					<span class="text-red-700 italic text-sm">{{ __($message) }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Realizovano') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="status" id="status">
					<option value="0">{{ __('Ne') }}</option>
					<option value="1" {{ $trainingPlan->rating != null ? "selected" : "" }} >{{ __('Da') }}</option>
				</select>
			</div>

			<div class="mb-4" id="final_num_field" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}} ">
				<label for="final_num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih') }} {{ __('realizovano') }}:</label>
				<input type="number" min="1" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="final_num_of_employees" name="final_num_of_employees" value="{{ $trainingPlan->final_num_of_employees  }}" oninvalid="this.setCustomValidity('{{ __("Izaberite broj zaposlenih") }}')" oninput="this.setCustomValidity('')" {{ $trainingPlan->final_num_of_employees != null ? 'required' : ''}} >
			</div>

			<div class="mb-4" id="rating_field" style="{{ $trainingPlan->rating != null ? 'display: ' : 'display: none'}} ">
				<label for="rating" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ocena') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="rating" id="rating" oninvalid="this.setCustomValidity('{{ __("Izaberite ocenu") }}')" oninput="this.setCustomValidity('')" {{ $trainingPlan->rating != null ? 'required' : ''}}>
					<option value="">{{ __('Izaberi') }}...</option>
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ $trainingPlan->rating != null && $trainingPlan->rating == $i ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
            </div>

            <div class="mb-4" id="documents" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}} ">
                <label for="documents" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Dokumenti') }}:</label>
            </div>

            <div class="mb-4 doc_field" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}} ">
                <label for="name_file" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Sertifikat / Diploma / Izveštaj sa obuke') }}:</label>
                <label for="name_file" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                    <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <small>{{__('Izaberi fajl')}}</small>
                </label>
                <input type="file" class="form-control-file d-none" id="name_file" name="file[]">
                <span class="font-italic text-s ml-2" id="old_document">{{__('Fajl nije izabran')}}</span>
                @error('file')
                    <br><span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror

                <span class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded cursor-pointer ml-3" id="addMore"><i class="fas fa-plus"> Dodaj još jedan dokument</i></span>

                <div id="more_fields"></div>
            </div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Izmeni') }}</button>
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
		dayOfWeekStart: 1,
		scrollInput: false
	});

	$('#status').change( () => {
		if($('#status').val() == 1){
			$('#final_num_field').css('display', '');
			$('#rating_field').css('display', '');
            $('.doc_field').css('display', '');
            $('#documents').css('display', '');
			$('#rating').attr('required', true);
			$('#final_num_of_employees').attr('required', true);
		}
		else{
			$('#final_num_field').css('display', 'none');
			$('#rating_field').css('display', 'none');
            $('.doc_field').css('display', 'none');
            $('#documents').css('display', 'none');
			$('#final_num_of_employees').val('');
			$('#rating').val('');
			$('#rating').attr('required', false);
			$('#final_num_of_employees').attr('required', false);
		}
	});

    $('#name_file').change( () => {
        let file = document.getElementById('name_file').files[0];
        if(file)
            document.getElementById('old_document').textContent = file.name;
    });

    $("#addMore").click(function(e) {
        e.preventDefault();
        if(sessionStorage.length == 0){
            sessionStorage.setItem('counter', 0);
            var counter = 0;
        }
        else{
            var counter = sessionStorage.getItem('counter');
            counter++;
        }
        console.log(sessionStorage.getItem('counter'));

        $('#more_fields').append(`
            <div class="flex" id="block">
                <label for="name_file${ counter }" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                    <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <small>{{__('Izaberi fajl')}}</small>
                </label>
                <input type="file" class="flex-1 form-control-file d-none" id="name_file${ counter }" name="file[]">
                <span class="flex-1 pt-3 font-italic text-s ml-2" id="old_document${ counter }">{{ __('Fajl nije izabran') }}</span>
                <button type="button" class="flex-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold ml-5 pb-2 rounded" onclick="parentElement.remove()"><i class="fas fa-trash"></i></button>
            </div>
        `);

        $('#name_file'+counter).change( () => {
            let file = document.getElementById('name_file'+counter).files[0];
            if(file)
                document.getElementById('old_document'+counter).textContent = file.name;
                sessionStorage.setItem('counter', counter);
        });
    });


</script>
