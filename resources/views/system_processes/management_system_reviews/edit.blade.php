<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zapisnik sa preispitivanja - Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('management-system-reviews.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('management-system-reviews.update', $msr->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
			@csrf
			@method('PUT')
			<div class="form-group">
				<label for="year">Godina:</label>
				<select class="form-control" name="year" id="year">
					@foreach(range(date('Y')-1, date('Y')+10) as $year)
						<option value="{{ $year }}" {{ $year == $msr->year ? "selected" : "" }}>{{ $year }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="participants">Učestvovali u preispitivanju:</label>
				<textarea class="form-control" id="participants" name="participants">{{ $msr->participants }}</textarea>
				@error('participants')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="measures_status">Status mera iz prethodnog preispitivanja:</label>
				<textarea class="form-control" id="measures_status" name="measures_status">{{ $msr->measures_status }}</textarea>
				@error('measures_status')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="internal_external_changes">Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta:</label>
				<textarea class="form-control" id="internal_external_changes" name="internal_external_changes">{{ $msr->internal_external_changes }}</textarea>
				@error('internal_external_changes')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="customer_satisfaction">Zadovoljstvo korisnika:</label>
				<textarea class="form-control" id="customer_satisfaction" name="customer_satisfaction">{{ $msr->customer_satisfaction }}</textarea>
				@error('customer_satisfaction')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="h5">Performanse procesa i usaglašenost proizvoda i usluga</p><hr>

			<div class="form-group">
				<label for="monitoring_measurement_results">Rezultati praćenja i merenja:</label>
				<textarea class="form-control" id="monitoring_measurement_results" name="monitoring_measurement_results">{{ $msr->monitoring_measurement_results }}</textarea>
				@error('monitoring_measurement_results')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="checks_results_desc">Dodatni opis rezultata internih provera:</label>
				<textarea class="form-control" id="checks_results_desc" name="checks_results_desc">{{ $msr->checks_results_desc }}</textarea>
				@error('checks_results_desc')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="resource_adequacy">Adekvatnost resursa:</label>
				<textarea class="form-control" id="resource_adequacy" name="resource_adequacy">{{ $msr->resource_adequacy }}</textarea>
				@error('resource_adequacy')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<p class="h5">Izlazne tačke preispitivanja</p><hr>

			<div class="form-group">
				<label for="improvement_opportunities ">Prilike za poboljšanje:</label>
				<textarea class="form-control" id="improvement_opportunities" name="improvement_opportunities">{{ $msr->improvement_opportunities }}</textarea>
				@error('improvement_opportunities')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="needs_for_change ">Potrebe za izmenama u sistemu menadžmenta:</label>
				<textarea class="form-control" id="needs_for_change" name="needs_for_change">{{ $msr->needs_for_change }}</textarea>
				@error('needs_for_change')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label for="needs_for_resources ">Potrebe za resursima:</label>
				<textarea class="form-control" id="needs_for_resources" name="needs_for_resources">{{ $msr->needs_for_resources }}</textarea>
				@error('needs_for_resources')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
            
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>
