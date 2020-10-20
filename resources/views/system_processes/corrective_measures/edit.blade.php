<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Neusaglašenosti i korektivne mere') }} - {{ __('Izmena') }} 
        </h2>
    </x-slot>

	<div class="row">
        <div class="col">
            <a class="btn btn-light" href="{{ route('corrective-measures.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
        </div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('corrective-measures.update', $corrective_measure->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="form-group">
				<label for="standard">Sistem menadžmenta:</label>
				<select class="form-control" name="standard" id="standard">
					<option value="">Izaberi...</option>
					@foreach($standards as $standard)
						<option value="{{ $standard->id }}" {{ $corrective_measure->standard_id == $standard->id ? "selected" : "" }} >{{ $standard->name }}</option>
					@endforeach
				</select>
				@error('standard')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="noncompliance_source">Izvor informacije o neusaglašenostima:</label>
				<select class="form-control" name="noncompliance_source" id="noncompliance_source">
					<option value="">Izaberi...</option>
					<option value="Eksterna provera" {{ $corrective_measure->noncompliance_source == "Eksterna provera" ? "selected" : "" }} >Eksterna provera</option>
					<option value="Interna provera" {{ $corrective_measure->noncompliance_source == "Interna provera" ? "selected" : "" }} >Interna provera</option>
					<option value="Preispitivanje ISM-a" {{ $corrective_measure->noncompliance_source == "Preispitivanje ISM-a" ? "selected" : "" }}>Preispitivanje ISM-a</option>
					<option value="Žalba" {{ $corrective_measure->noncompliance_source == "Žalba" ? "selected" : "" }}>Žalba</option>
					<option value="Ostalo" {{ $corrective_measure->noncompliance_source == "Ostalo" ? "selected" : "" }}>Ostalo</option>
				</select>
				@error('noncompliance_source')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="sector">Organizaciona celina u kojoj je utvrđena neusaglašenost:</label>
				<select class="form-control" name="sector" id="sector">
					<option value="">Izaberi...</option>
					@foreach($sectors as $sector)
						<option value="{{ $sector->id }}" {{ $corrective_measure->sector_id == $sector->id ? "selected" : "" }} >{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="noncompliance_description">Opis neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_description" name="noncompliance_description">{{ $corrective_measure->noncompliance_description }}</textarea>
				@error('noncompliance_description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="noncompliance_cause">Uzrok neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_cause" name="noncompliance_cause">{{ $corrective_measure->noncompliance_cause }}</textarea>
				@error('noncompliance_cause')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="measure">Mera za otklanjanje neusaglašenosti:</label>
				<textarea class="form-control" id="measure" name="measure">{{ $corrective_measure->measure }}</textarea>
				@error('measure')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="measure_approval">Odobravanje mere:</label>
				<select class="form-control" name="measure_approval" id="measure_approval">
					<option value="1" {{ $corrective_measure->measure_approval == "1" ? "selected" : "" }} >DA</option>
					<option value="0" {{ $corrective_measure->measure_approval == "0" ? "selected" : "" }} >NE</option>
				</select>
			</div>
			<div class="form-group" id="measure_reason_field" style="{{ ($corrective_measure->measure_approval == "0")? 'display:' : 'display: none' }}">
				<label for="measure_approval_reason">Razlog neodobravanja mere</label>
				<input type="text" class="form-control" name="measure_approval_reason" id="measure_approval_reason" value="{{ $corrective_measure->measure_approval_reason }}">
			</div>
			<div class="form-group">
				<label for="measure_status">Status mere:</label>
				<select class="form-control" name="measure_status" id="measure_status">
					<option value="0" {{ $corrective_measure->measure_status == "0" ? "selected" : "" }} >NE</option>
					<option value="1" {{ $corrective_measure->measure_status == "1" ? "selected" : "" }} >DA</option>
				</select>
			</div>
			<div class="form-group" id="measure_effective_field" style="{{ ($corrective_measure->measure_status == "1")? 'display:' : 'display: none' }}">
				<label for="measure_effective">Mera efektivna:</label>
				<select class="form-control" name="measure_effective" id="measure_effective">
					<option value="">Izaberi...</option>
					<option value="1" {{ $corrective_measure->measure_effective == "1" ? "selected" : "" }} >DA</option>
					<option value="0" {{ $corrective_measure->measure_effective == "0" ? "selected" : "" }} >NE</option>
				</select>
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>

<script>

	$('#measure_approval').change( () => {
		if($('#measure_approval').val() == 0){
			$('#measure_reason_field').css('display', '');
		}
		else{
			$('#measure_reason_field').css('display', 'none');
			$('#measure_approval_reason').val('');
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