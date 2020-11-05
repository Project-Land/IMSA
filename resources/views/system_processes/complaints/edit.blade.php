<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Reklamacija') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('complaints.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('complaints.update', $complaint->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')
			
            <div class="mb-4">
				<label for="name" class="block text-gray-700 text-sm font-bold mb-2">Oznaka:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  id="name" name="name" value="{{ $complaint->name }}" autofocus required oninvalid="this.setCustomValidity('Oznaka nije popunjena')"
                oninput="this.setCustomValidity('')">
				@error('name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="submission_date" class="block text-gray-700 text-sm font-bold mb-2">Datum podnošenja reklamacije:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  id="submission_date" name="submission_date" value="{{ date('d.m.Y', strtotime($complaint->submission_date)) }}" placeholder="xx.xx.xxxx" required oninvalid="this.setCustomValidity('Datum podnošenja reklamacije nije popunjen')"
                oninput="this.setCustomValidity('')">
				
				@error('submission_date')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="description" class="block text-gray-700 text-sm font-bold mb-2">Opis reklamacije:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  id="description" name="description" required oninvalid="this.setCustomValidity('Opis reklamacije nije popunjen')"
                oninput="this.setCustomValidity('')">{{ $complaint->description }}</textarea>
				@error('description')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="process" class="block text-gray-700 text-sm font-bold mb-2">Proces na koji se reklamacija odnosi:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  id="process" name="process" value="{{ $complaint->process }}" required oninvalid="this.setCustomValidity('Proces na koji se reklamacija odnosi nije popunjen')"
                oninput="this.setCustomValidity('')">
				@error('process')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="accepted" class="block text-gray-700 text-sm font-bold mb-2">Da li je reklamacija opravdana / prihvaćena:</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="accepted" name="accepted">
					<option value="0" {{ $complaint->accepted == 0 ? "selected" : "" }} >NE</option>
					<option value="1" {{ $complaint->accepted == 1 ? "selected" : "" }} >DA</option>
				</select>
			</div>

			<div class="{{ $complaint->accepted == 1 ? "" : "d-none" }}" id="complaint_accepted">

				<div class="mb-4">
					<label for="deadline_date" class="block text-gray-700 text-sm font-bold mb-2">Rok za realizaciju reklamacije:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline_date" name="deadline_date"  value="{{ $complaint->deadline_date != null ? date('d.m.Y', strtotime($complaint->deadline_date)) : date('d.m.Y') }}" required placeholder="xx.xx.xxxx" oninvalid="this.setCustomValidity('Rok za realizaciju reklamacije nije popunjen')"
                oninput="this.setCustomValidity('')">
				</div>

				<div class="mb-4">
					<label for="responsible_person" class="block text-gray-700 text-sm font-bold mb-2">Lice odgovorno za rešavanje reklamacije:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="responsible_person" name="responsible_person" value="{{ $complaint->responsible_person }}" required oninvalid="this.setCustomValidity('Lice odgovorno za rešavanje reklamacije nije popunjeno')"
                oninput="this.setCustomValidity('')">
					@error('responsible_person')
						<span class="text-red-700 italic text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="way_of_solving" class="block text-gray-700 text-sm font-bold mb-2">Način rešavanja reklamacije</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="way_of_solving" name="way_of_solving" value="{{ $complaint->way_of_solving }}" 
					required oninvalid="this.setCustomValidity('Način rešavanja reklamacije nije popunjen')"
                oninput="this.setCustomValidity('')">
					@error('way_of_solving')
						<span class="text-red-700 italic text-sm">{{ $message }}</span>
					@enderror
				</div>

				<div class="mb-4">
					<label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status reklamacije:</label>
					<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="status" name="status">
						<option value="1" {{ $complaint->status == 1 ? "selected" : "" }} >Otvorena</option>
						<option value="0" {{ $complaint->status == 0 ? "selected" : "" }} >Zatvorena</option>
					</select>
				</div>

				<div class="mb-4 d-none" id="closing_date_block">
					<label for="closing_date" class="block text-gray-700 text-sm font-bold mb-2">Datum zatvaranja:</label>
					<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="closing_date" name="closing_date" value="{{ $complaint->closing_date }}" disabled>
				</div>

			</div>

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Izmeni</button>
		</form>
    </div>

</x-app-layout>

<script>
	$('#submission_date').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
		dayOfWeekStart: 1,
		scrollInput: false
	});

	$('#deadline_date').datetimepicker({
		timepicker: false,
		format: 'd.m.Y',
		minDate: 0,
		dayOfWeekStart: 1,
		scrollInput: false
	});

	$('#accepted').change( () => {
		if($('#accepted').val() == 1){
			$('#complaint_accepted').removeClass('d-none');
		}
		else{
			$('#complaint_accepted').addClass('d-none');
			$('#deadline_date').val('');
			$('#responsible_person').val('');
			$('#way_of_solving').val('');
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
</script>