<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Neusaglašenosti i korektivne mere') }} - {{ __('Kreiranje') }} 
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('corrective-measures.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('corrective-measures.store') }}" method="POST">
			@csrf
			<div class="form-group">
				<label for="standard">Sistem menadžmenta:</label>
				<select class="form-control" name="standard" id="standard">
					<option value="">Izaberi...</option>
					@foreach($standards as $standard)
						<option value="{{ $standard->id }}">{{ $standard->name }}</option>
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
					<option value="Eksterna provera" {{ old('noncompliance_source') == "Eksterna provera" ? "selected" : "" }}>Eksterna provera</option>
					<option value="Interna provera" {{ old('noncompliance_source') == "Interna provera" ? "selected" : "" }}>Interna provera</option>
					<option value="Preispitivanje ISM-a" {{ old('noncompliance_source') == "Preispitivanje ISM-a" ? "selected" : "" }}>Preispitivanje ISM-a</option>
					<option value="Žalba" {{ old('noncompliance_source') == "Žalba" ? "selected" : "" }}>Žalba</option>
					<option value="Ostalo" {{ old('noncompliance_source') == "Ostalo" ? "selected" : "" }}>Ostalo</option>
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
						<option value="{{ $sector->id }}">{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="noncompliance_description">Opis neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_description" name="noncompliance_description">{{ old('noncompliance_description') }}</textarea>
				@error('noncompliance_description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="noncompliance_cause">Uzrok neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_cause" name="noncompliance_cause">{{ old('noncompliance_cause') }}</textarea>
				@error('noncompliance_cause')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="measure">Mera za otklanjanje neusaglašenosti:</label>
				<textarea class="form-control" id="measure" name="measure">{{ old('measure') }}</textarea>
				@error('measure')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="measure_approval">Odobravanje mere:</label>
				<select class="form-control" name="measure_approval" id="measure_approval">
					<option value="1" {{ old('measure_approval') == '1' ? "selected" : "" }} >DA</option>
					<option value="0" {{ old('measure_approval') == '0' ? "selected" : "" }} >NE</option>
				</select>
			</div>
			<div class="form-group" id="measure_reason_field" style="display: none">
				<label for="measure_approval_reason">Razlog neodobravanja mere</label>
				<input type="text" class="form-control" name="measure_approval_reason" id="measure_approval_reason">
			</div>
			<div class="form-group">
				<label for="measure_status">Status mere:</label>
				<select class="form-control" name="measure_status" id="measure_status">
					<option value="0" {{ old('measure_status') == '0' ? "selected" : "" }} >NE</option>
					<option value="1" {{ old('measure_status') == '1' ? "selected" : "" }} >DA</option>
				</select>
			</div>
			<div class="form-group" id="measure_effective_field" style="display: none">
				<label for="measure_effective">Mera efektivna:</label>
				<select class="form-control" name="measure_effective" id="measure_effective">
					<option value="">Izaberi...</option>
					<option value="1" {{ old('measure_effective') == '1' ? "selected" : "" }} >DA</option>
					<option value="0" {{ old('measure_effective') == '0' ? "selected" : "" }} >NE</option>
				</select>
			</div>
			<button type="submit" class="btn btn-success">Kreiraj</button>
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
