<x-app-layout>
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <x-slot name="header">
    <div class="flex flex-row justify-between">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izmena obuke') }}
        </h2>
        @include('includes.video')
    </div>
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
				<label for="num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih - planirano') }}:</label>
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
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="status">
					<option value="0">{{ __('Ne') }}</option>
					<option value="1" {{ $trainingPlan->rating != null ? "selected" : "" }} >{{ __('Da') }}</option>
				</select>
			</div>

			<div class="mb-4" id="final_num_field" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}}">
				<label for="final_num_of_employees" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Broj zaposlenih - realizovano') }}:</label>
				<input type="number" min="1" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="final_num_of_employees" name="final_num_of_employees" value="{{ $trainingPlan->final_num_of_employees  }}" oninvalid="this.setCustomValidity('{{ __("Izaberite broj zaposlenih") }}')" oninput="this.setCustomValidity('')" {{ $trainingPlan->final_num_of_employees != null ? 'required' : ''}} >
			</div>

			<div class="mb-4" id="rating_field" style="{{ $trainingPlan->rating != null ? 'display: ' : 'display: none'}}">
				<label for="rating" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ocena') }}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="rating" id="rating" oninvalid="this.setCustomValidity('{{ __("Izaberite ocenu") }}')" oninput="this.setCustomValidity('')" {{ $trainingPlan->rating != null ? 'required' : ''}}>
					<option value="">{{ __('Izaberi') }}...</option>
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ $trainingPlan->rating != null && $trainingPlan->rating == $i ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
            </div>

            <div class="mb-2 flex justify-between docs" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}}">
                <label for="name_file" class="block text-gray-700 text-sm font-bold pt-2">{{ __('Sertifikat / Diploma / Izveštaj sa obuke') }}:</label>
                <span class="w-auto bg-blue-500 hover:bg-blue-700 text-white text-xs sm:text-sm mt-1 sm:mt-0 font-bold py-2 px-4 focus:outline-none focus:shadow-outline cursor-pointer ml-3" id="addGroup"><i class="fas fa-plus"></i>  {{ __('Dodaj novi unos') }}</span>
            </div>

            @foreach($trainingPlan->docArray as $document)
                <div class="mb-4 doc_field border-2 border-dashed">
                    <button data-toggle="tooltip" data-placement="top" title="{{ __('Ukloni ceo unos') }}" type="button" class="text-lg flex-1 float-right py-2 bg-red-700 text-white font-semibold rounded px-4" onclick="parentElement.remove(); $('body>.tooltip').remove();"><i class="fas fa-trash"></i></button>
                    <div class="mb-2 flex items-center">
                        <label for="oldFile{{ $document->id }}" class="mt-2 w-50 text-sm"><span class="font-bold">{{ __('Dokument') }}: </span><span class="italic">{{ $document->file_name }}</span></label>
                        <input class="bg-white" type="hidden" id="{{ $document->id }}" name="training[{{ $document->id }}][oldFile]" value="{{ $document->id }}" >
                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Obriši dokument') }}" type="button" class="text-white font-bold py-1 px-2 bg-red-700 rounded-sm" onclick="parentElement.remove(); $('body>.tooltip').remove();"><i class="fas fa-minus-circle"></i></button>
                    </div>
                    <input class="bg-white" type="hidden" id="deletedFile{{ $document->id }}" name="training[{{ $document->id }}][deletedFile]" value="{{ $document->id }}" >

                    <div class="mb-2">
                        <label for="name_file{{ $document->id }}" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                            <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                            </svg>
                            <small>{{__('Izaberi fajl')}}</small>
                        </label>
                        <input type="file" class="form-control-file d-none" id="name_file{{ $document->id }}" name="training[{{ $document->id }}][file][]" onchange="showFileName({{ $document->id }})" multiple>

                        <span class="font-italic text-xs sm:text-sm ml-2" id="old_document{{ $document->id }}">{{__('Fajl nije izabran')}}</span>
                        @error('name_file')
                            <br><span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror

                        <button type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Ukloni odabrane fajlove') }}" class="flex-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold ml-5 pb-2 rounded" onclick="clearOldFile({{ $document->id }});"><i class="fas fa-times-circle"></i></button>
                    </div>

                    <div class="mb-4" id="users-field">
                        <label for="users" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Učesnici') }}:</label>
                        <select class="js-example-basic-multiple" name="training[{{ $document->id }}][users][]" multiple="multiple" style="width: 100%;">
                            @foreach($users as $user)
                                @if($user->teams[0]->membership->role != 'super-admin')
                                    <option value="{{ $user->id }}" {{ $document->users->contains($user->id)? "selected":"" }}>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach

            @if($trainingPlan->withoutDoc->count())
                <div class="mb-4 doc_field border-2 border-dashed">
                    <button type="button" class="text-lg flex-1 float-right py-2 bg-red-700 text-white font-semibold rounded px-4" onclick="parentElement.remove()"><i class="fas fa-trash"></i></button>

                    <div class="mb-2">
                        <label for="name_file0" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                            <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                            </svg>
                            <small>{{__('Izaberi fajl')}}</small>
                        </label>
                        <input type="file" class="form-control-file d-none" id="name_file0" name="training[0][file][]" onchange="showFileName(0)" multiple>

                        <span class="font-italic text-xs sm:text-sm ml-2" id="old_document0">{{__('Fajl nije izabran')}}</span>
                        @error('file')
                            <br><span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror

                        <button type="button" data-toggle="tooltip" data-placement="top" title="{{ __('Ukloni odabrane fajlove') }}" type="button" class="flex-1 bg-transparent hover:bg-red-500 text-red-700 font-semibold ml-5 pb-2 rounded" onclick="clearOldFile(0)"><i class="fas fa-times-circle"></i></button>
                    </div>

                    <div class="mb-4" id="users-field">
                        <label for="users" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Učesnici') }}:</label>
                        <select class="js-example-basic-multiple" name="training[0][users][]" multiple="multiple" style="width: 100%;">
                            @foreach($users as $user)
                                @if($user->teams[0]->membership->role != 'super-admin')
                                    <option value="{{ $user->id }}" {{ $trainingPlan->withoutDoc->contains($user->id)? "selected":"" }}>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div id="more_fields" class="mt-3 sm:mt-0"></div>

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

    .select2-results {
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        padding-top: 5px;
        padding-bottom: 10px;
        border: 1px solid #dee2e6 !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
    }
    .select2-selection__choice {
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        border-radius: 1px;
    }
</style>

<script>
	var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

    $('.js-example-basic-multiple').select2();

    $('[data-toggle="tooltip"]').tooltip();

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
            $('#users-field').removeClass('d-none');
            $('#documents').css('display', '');
            $('.docs').css('display', '');
			$('#rating').attr('required', true);
			$('#final_num_of_employees').attr('required', true);
		}
		else{
			$('#final_num_field').css('display', 'none');
			$('#rating_field').css('display', 'none');
            $('.doc_field').css('display', 'none');
            $('#users-field').addClass('d-none');
            $('#documents').css('display', 'none');
            $('.docs').css('display', 'none');
			$('#final_num_of_employees').val('');
			$('#rating').val('');
			$('#rating').attr('required', false);
			$('#final_num_of_employees').attr('required', false);
            $('.js-example-basic-multiple').val(null).trigger('change');
		}
	});

    function showFileName(id){
        let file = document.getElementById(`name_file${id}`).files;
        document.getElementById(`old_document${id}`).textContent = "";
        for(f of file){
            if(f)
                document.getElementById(`old_document${id}`).textContent += f.name + ", ";
        }
    }

    function clearOldFile(id){
        document.getElementById(`name_file${id}`).value = "";
        document.getElementById(`old_document${id}`).textContent = "{{ __('Fajl nije izabran') }}";
    }

    let groupCounter = 1;

    $("#addGroup").click(function(e) {

        $('#more_fields').append(`
        <div class="border-2 border-dashed my-3">
            <div class="mb-2" id="block">
                <label for="new_name_file${ groupCounter }" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                    <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <small>{{__('Izaberi fajl')}}</small>
                </label>
                <input type="file" class="flex-1 form-control-file d-none" id="new_name_file${ groupCounter }" name="newTraining[${ groupCounter }][file][]" multiple>
                <span class="flex-1 pt-3 font-italic text-xs sm:text-sm ml-2" id="new_old_document${ groupCounter }">{{ __('Fajl nije izabran') }}</span>
                <button type="button" class="text-lg flex-1 ml-11 bg-transparent hover:bg-red-500 text-red-700 font-semibold pb-2 rounded" onclick="clearFile(${ groupCounter })"><i class="fas fa-times-circle"></i></button>
                <button data-toggle="tooltip" data-placement="top" title="{{ __('Ukloni ceo unos') }}" type="button" class="text-lg flex-1 float-right py-2 bg-red-700 text-white font-semibold rounded px-4" onclick="parentElement.parentElement.remove(); $('body>.tooltip').remove();"><i class="fas fa-trash"></i></button>
            </div>
            <div class="mb-4" id="users-field${ groupCounter }">
                <label for="users" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Učesnici') }}:</label>
                <select class="js-example-basic-multiple" name="newTraining[${ groupCounter }][users][]" multiple="multiple" style="width: 100%;">
                    @foreach($users as $user)
                        @if($user->teams[0]->membership->role != 'super-admin')
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        `);

        $('.js-example-basic-multiple').select2();

        let cnt = groupCounter.toString();

        $('#new_name_file'+cnt).change( () => {

            let file = document.getElementById(`new_name_file${cnt}`).files;
            document.getElementById(`new_old_document${cnt}`).textContent = "";
            for(f of file){
                if(f)
                    document.getElementById(`new_old_document${cnt}`).textContent += f.name + ", ";
                    sessionStorage.setItem('counter', cnt);
            }
        });

        groupCounter++;

        $('[data-toggle="tooltip"]').tooltip();
    });

    function clearFile(id){
        document.getElementById(`new_name_file${id}`).value = "";
        document.getElementById(`new_old_document${id}`).textContent = "{{ __('Fajl nije izabran') }}";
    }


</script>
