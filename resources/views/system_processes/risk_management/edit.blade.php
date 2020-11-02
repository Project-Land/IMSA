<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Upravljanje rizicima') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('risk-management.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('risk-management.update', $risk->id) }}" method="POST">
			@csrf
            @method('PUT')
			<div class="form-group">
				<label for="description">Opis:</label>
				<textarea class="form-control" id="description" name="description" autofocus>{{ $risk->description }}</textarea>
				@error('description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="probability">Verovatnoća:</label>
                <select class="form-control" name="probability" id="probability">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->probability) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
			</div>
            <div class="form-group">
				<label for="frequency">Učestalost:</label>
                <select class="form-control" name="frequency" id="frequency">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->frequency) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
			</div>
            <div class="form-group">
				<label for="total">Ukupno:</label>
				<input class="form-control" type="text" name="total" id="total" disabled value="{{ $risk->total }}">
			</div>
            <div class="form-group">
				<label for="acceptable">Prihvatljivo:</label>
                <select class="form-control" name="acceptable" id="acceptable">
                    @for($i = 1; $i <= 25; $i++)
                        <option value="{{ $i }}" {{ ($i == $risk->acceptable) ? 'selected' : '' }} >{{ $i }}</option>
                    @endfor
                </select>
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>

<script>

    function calculate(){
        let probability = $('#probability').val();
        let frequency = $('#frequency').val();
        let total = probability * frequency;
        $('#total').val(total);
    }

    $('#probability').change( () => {
        calculate();
    })

    $('#frequency').change( () => {
        calculate();
    })

</script>