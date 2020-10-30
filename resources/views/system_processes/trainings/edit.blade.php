<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Godišnji plan obuka') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('trainings.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('trainings.update', $trainingPlan->id) }}" method="POST">
			@csrf
            @method('PUT')
            <div class="form-group">
				<label for="name">Naziv obuke:</label>
				<input type="text" class="form-control" id="name" name="name" value="{{ $trainingPlan->name }}" autofocus>
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="description">Opis obuke:</label>
                <textarea class="form-control" id="description" name="description">{{ $trainingPlan->description  }}</textarea>
				@error('description')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="type">Vrsta obuke:</label>
				<select class="form-control" name="type" id="type">
					<option value="Interna" {{ $trainingPlan->type == "Interna" ? "selected": "" }} >Interna</option>
					<option value="Eksterna" {{ $trainingPlan->type == "Eksterna" ? "selected": "" }} >Eksterna</option>
				</select>
			</div>
			<div class="form-group">
				<label for="num_of_employees">Broj zaposlenih:</label>
				<input type="number" min="1" class="form-control" id="num_of_employees" name="num_of_employees" value="{{ $trainingPlan->num_of_employees  }}">
				@error('num_of_employees')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="training_date">Planirani termin:</label>
				<input type="text" class="form-control" id="training_date" name="training_date" value="{{ date('d.m.Y H:i', strtotime($trainingPlan->training_date))  }}">
				@error('training_date')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="place">Mesto obuke:</label>
				<input type="text" class="form-control" id="place" name="place" value="{{ $trainingPlan->place  }}">
				@error('place')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="resources">Resursi:</label>
				<textarea class="form-control" id="resources" name="resources">{{ $trainingPlan->resources  }}</textarea>
				@error('resources')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="status">Realizovano:</label>
				<select class="form-control" name="status" id="status">
					<option value="0">NE</option>
					<option value="1" {{ $trainingPlan->rating != null ? "selected" : "" }} >DA</option>
				</select>
			</div>
			<div class="form-group" id="final_num_field" style="{{ $trainingPlan->final_num_of_employees != null ? 'display: ' : 'display: none'}} ">
				<label for="final_num_of_employees">Broj zaposlenih realizovano:</label>
				<input type="number" min="1" class="form-control" id="final_num_of_employees" name="final_num_of_employees" value="{{ $trainingPlan->final_num_of_employees  }}">
			</div>
			<div class="form-group" id="rating_field" style="{{ $trainingPlan->rating != null ? 'display: ' : 'display: none'}} ">
				<label for="rating">Ocena:</label>
				<select class="form-control" name="rating" id="rating">
					<option value="">Izaberi...</option>
					@for($i = 1; $i <= 5; $i++)
						<option value="{{ $i }}" {{ $trainingPlan->rating != null && $trainingPlan->rating == $i ? "selected" : "" }} >{{ $i }}</option>
					@endfor
				</select>
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
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
	jQuery.datetimepicker.setLocale('sr');
   	$('#training_date').datetimepicker({
		format: 'd.m.Y H:i',
	});

	$('#status').change( () => {
		if($('#status').val() == 1){
			$('#final_num_field').css('display', '');
			$('#rating_field').css('display', '');
		}
		else{
			$('#final_num_field').css('display', 'none');
			$('#rating_field').css('display', 'none');
			$('#final_num_of_employees').val('');
			$('#rating').val('');
		}
	})
</script>
