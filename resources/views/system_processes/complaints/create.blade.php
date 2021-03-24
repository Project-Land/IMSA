<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Reklamacije') }}  - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('complaints.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf

			<div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{__('Oznaka')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" value="{{ old('name') }}" autofocus required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="submission_date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum podnošenja reklamacije')}}:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="submission_date" name="submission_date" value="{{ old('submission_date') }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
				@error('submission_date')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">{{__('Opis reklamacije')}}:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                oninput="this.setCustomValidity('')">{{ old('description') }}</textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4 doc_field">
                <label for="name_file" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Dokument') }}:</label>
                <label for="name_file" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                    <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <small>{{__('Izaberi fajl')}}</small>
                </label>
                <input type="file" class="form-control-file d-none" id="name_file" name="file[]">
                <span class="font-italic text-xs sm:text-sm ml-2" id="old_document">{{__('Fajl nije izabran')}}</span>
                @error('file')
                    <br><span class="text-red-700 italic text-sm">{{ $message }}</span>
                @enderror

                <span class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 text-xs sm:text-sm w-full mt-1 sm:mt-0 focus:outline-none focus:shadow-outline cursor-pointer ml-3" id="addMore"><i class="fas fa-plus"></i>  {{ __('Dodaj još jedan dokument') }}</span>

                <div id="more_fields" class="mt-3 sm:mt-0"></div>
            </div>

			<div class="mb-4">
				<label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">{{__('Proces na koji se reklamacija odnosi')}}:</label>

                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="sector_id" name="sector_id" required oninvalid="this.setCustomValidity('{{ __("Izaberite proces") }}')" oninput="this.setCustomValidity('')">
                    <option value="">{{ __('Izaberite') }}...</option>
                    @foreach($sectors as $sector)
                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                    @endforeach
                </select>
				@error('sector_id')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="accepted" class="block text-gray-700 text-sm font-bold mb-2">{{__('Da li je reklamacija opravdana / prihvaćena')}}:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="accepted" name="accepted" >
					<option value="0" selected>{{__('NE')}}</option>
					<option value="1">{{__('DA')}}</option>
				</select>
			</div>

			<div class="{{ old('accepted') == 0 ? "d-none" : "" }}" id="complaint_accepted">

				<div class="mb-4">
					<label for="deadline_date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Rok za realizaciju reklamacije')}}:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline" name="deadline_date" placeholder="xx.xx.xxxx">
					@error('deadline_date')
						<span class="text-red-700 italic text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="responsible_person" class="block text-gray-700 text-sm font-bold mb-2">{{__('Lice odgovorno za rešavanje reklamacije')}}:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}" oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
					@error('responsible_person')
						<span class="text-red-700 italic text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="way_of_solving" class="block text-gray-700 text-sm font-bold mb-2">{{__('Način rešavanja reklamacije')}}</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="way_of_solving" name="way_of_solving" value="{{ old('way_of_solving') }}" oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')">
					@error('way_of_solving')
						<span class="text-red-700 italic text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="status" class="block text-gray-700 text-sm font-bold mb-2">{{__('Status reklamacije')}}:</label>
					<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="status" name="status">
						<option value="1">{{__('Otvorena')}}</option>
						<option value="0" selected>{{__('Zatvorena')}}</option>
					</select>
				</div>

				<div class="mb-4 d-none" id="closing_date_block">
					<label for="closing_date" class="block text-gray-700 text-sm font-bold mb-2">{{__('Datum zatvaranja')}}:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="closing_date" name="closing_date" disabled>
				</div>

			</div>

			<button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{__('Kreiraj')}}</button>
		</form>
    </div>

</x-app-layout>

<script>
    var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
    $.datetimepicker.setLocale(lang);

	$('#submission_date').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
		minDate: 1,
		dayOfWeekStart: 1,
    	scrollInput: false
	});

	$('#deadline').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
		minDate: 0,
		dayOfWeekStart: 1,
    	scrollInput: false
	});

	$('#submission_date').change( () => {
		let start_date = $('#submission_date').val().split(" ")[0].split(".").reverse().join("/").toString().trim();
		$('#deadline').datetimepicker({
			minDate: start_date
		})
	})

	$('#accepted').change( () => {
		if($('#accepted').val() == 1){
			$('#complaint_accepted').removeClass('d-none');
			$('#responsible_person').attr('required', true);
			$('#way_of_solving').attr('required', true);
		}
		else{
			$('#complaint_accepted').addClass('d-none');
			$('#deadline_date').val('');
			$('#responsible_person').val('');
			$('#way_of_solving').val('');
			$('#responsible_person').attr('required', false);
			$('#way_of_solving').attr('required', false);
		}
	})

	$('#status').change( () => {
		if($('#status').val() == 0){
			$('#closing_date_block').removeClass('d-none');
			$('#closing_date').val(new Date().toLocaleDateString('sr-SR', { timeZone: 'CET' }));
		}
		else{
			$('#closing_date_block').addClass('d-none');
		}
	})


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
			sessionStorage.setItem('counter',counter );
        }


        $('#more_fields').append(`
            <div class="flex" id="block">
                <label for="name_file${ counter }" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                    <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <small>{{__('Izaberi fajl')}}</small>
                </label>
                <input type="file" class="flex-1 form-control-file d-none" id="name_file${ counter }" name="file[]">
                <span class="flex-1 pt-3 font-italic text-xs sm:text-sm ml-2" id="old_document${ counter }">{{ __('Fajl nije izabran') }}</span>
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
