<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Plan za postupanje sa rizikom') }}  - {{ __('Kreiranje/Izmena plana') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

        <div class="row">
            <div class="col">
                <p>{{ $risk->description }} - {{ date('d.m.Y', strtotime($risk->measure_created_at)) }}</p>
            </div>
        </div>

		<form action="{{ route('risk-management.update-plan', $risk->id) }}" method="POST">
			@csrf
            @method('PUT')
			<div class="form-group">
				<label for="cause">Uzrok:</label>
				<input type="text" class="form-control" id="cause" name="cause" value="{{ $risk->cause }}" autofocus>
				@error('cause')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="risk_lowering_measure">Mera za smanjenje rizika:</label>
				<input type="text" class="form-control" id="risk_lowering_measure" name="risk_lowering_measure" value="{{ $risk->risk_lowering_measure }}">
				@error('risk_lowering_measure')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="responsibility">Odgovoronost:</label>
				<input type="text" class="form-control" id="responsibility" name="responsibility" value="{{ $risk->responsibility }}">
				@error('responsibility')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="deadline">Rok za realizaciju:</label>
				<input type="date" class="form-control" id="deadline" name="deadline" value="{{ $risk->deadline }}">
				@error('deadline')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="status">Status:</label>
				<select class="form-control" name="status" id="status">
                    <option value="1" {{ ($risk->status == 1)? "selected" : "" }} >Otvorena</option>
                    <option value="0" {{ ($risk->status == 0)? "selected" : "" }} >Zatvorena</option>
                </select>
			</div>
			<button type="submit" class="btn btn-success">Primeni</button>
		</form>
    </div>

</x-app-layout>