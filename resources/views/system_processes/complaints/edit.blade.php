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

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
			@csrf
            @method('PUT')
            <div class="form-group">
				<label for="name">Oznaka:</label>
				<input type="text" class="form-control" id="name" name="name" value="{{ $complaint->name }}" autofocus>
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="submission_date">Datum podnošenja reklamacije:</label>
				<input type="date" class="form-control" id="submission_date" name="submission_date" value="{{ $complaint->submission_date }}" autofocus>
				@error('submission_date')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="description">Opis reklamacije:</label>
                <textarea class="form-control" id="description" name="description">{{ $complaint->description }}</textarea>
				@error('description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="process">Proces na koji se reklamacija odnosi:</label>
				<input type="text" class="form-control" id="process" name="process" value="{{ $complaint->process }}">
				@error('process')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="accepted">Da li je reklamacija opravdana / prihvaćena:</label>
				<select  class="form-control" id="accepted" name="accepted">
					<option value="0" {{ $complaint->accepted == 0 ? "selected" : "" }} >NE</option>
					<option value="1" {{ $complaint->accepted == 1 ? "selected" : "" }} >DA</option>
				</select>
			</div>

			<div class="{{ $complaint->accepted == 1 ? "" : "d-none" }}" id="complaint_accepted">
				<div class="form-group">
					<label for="deadline_date">Rok za realizaciju reklamacije:</label>
					<input type="date" class="form-control" id="deadline_date" name="deadline_date" value="{{ $complaint->deadline_date }}" autofocus>
				</div>
				<div class="form-group">
					<label for="responsible_person">Lice odgovorno za rešavanje reklamacije:</label>
					<input type="text" class="form-control" id="responsible_person" name="responsible_person" value="{{ $complaint->responsible_person }}" autofocus>
					@error('responsible_person')
						<span class="text-danger">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group">
					<label for="way_of_solving">Način rešavanja reklamacije</label>
					<input type="text" class="form-control" id="way_of_solving" name="way_of_solving" value="{{ $complaint->way_of_solving }}" autofocus>
					@error('way_of_solving')
						<span class="text-danger">{{ $message }}</span>
					@enderror
				</div>
				<div class="form-group">
					<label for="status">Status reklamacije:</label>
					<select  class="form-control" id="status" name="status">
						<option value="1" {{ $complaint->status == 1 ? "selected" : "" }} >Otvorena</option>
						<option value="0" {{ $complaint->status == 0 ? "selected" : "" }} >Zatvorena</option>
					</select>
				</div>
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>

<script>
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
</script>