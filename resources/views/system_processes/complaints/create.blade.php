<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Reklamacije') }}  - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('complaints.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('complaints.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			<div class="form-group">
				<label for="name">Oznaka:</label>
				<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" autofocus>
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="submission_date">Datum podnošenja reklamacije:</label>
				<input type="text" class="form-control" id="submission_date" name="submission_date" value="{{ old('submission_date') }}">
				@error('submission_date')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="description">Opis reklamacije:</label>
                <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
				@error('description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="process">Proces na koji se reklamacija odnosi:</label>
				<input type="text" class="form-control" id="process" name="process" value="{{ old('process') }}">
				@error('process')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="accepted">Da li je reklamacija opravdana / prihvaćena:</label>
				<select  class="form-control" id="accepted" name="accepted">
					<option value="0" selected>NE</option>
					<option value="1">DA</option>
				</select>
			</div>

			<div class="d-none" id="complaint_accepted">
				<div class="form-group">
					<label for="deadline_date">Rok za realizaciju reklamacije:</label>
					<input type="text" class="form-control" id="deadline_date" name="deadline_date" value="{{ old('deadline_date') }}">
				</div>
				<div class="form-group">
					<label for="responsible_person">Lice odgovorno za rešavanje reklamacije:</label>
					<input type="text" class="form-control" id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}">
					@error('responsible_person')
						<span class="text-danger">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group">
					<label for="way_of_solving">Način rešavanja reklamacije</label>
					<input type="text" class="form-control" id="way_of_solving" name="way_of_solving" value="{{ old('way_of_solving') }}">
					@error('way_of_solving')
						<span class="text-danger">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group">
					<label for="status">Status reklamacije:</label>
					<select  class="form-control" id="status" name="status">
						<option value="1" selected>Otvorena</option>
						<option value="0">Zatvorena</option>
					</select>
				</div>
				<div class="form-group d-none" id="closing_date_block">
					<label for="closing_date">Datum zatvaranja:</label>
					<input class="form-control" type="text" id="closing_date" name="closing_date" disabled>
				</div>
			</div>
            
			<button type="submit" class="btn btn-success">Kreiraj</button>
		</form>
    </div>

</x-app-layout>

<script>
	$('#submission_date').datetimepicker({
		timepicker:false,
		format:'d.m.Y'
	});

	$('#deadline_date').datetimepicker({
		timepicker:false,
		format:'d.m.Y'
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