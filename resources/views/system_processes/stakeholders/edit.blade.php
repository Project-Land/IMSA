<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col-sm-2 offset-sm-10">
        	<a class="btn btn-light" href="{{ route('stakeholders.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
	</div>
	
	<div class="row">
		<div class="col-sm-6 offset-sm-3">
			<div class="card shadow-sm">
				<div class="card-body">
					<form action="{{ route('stakeholders.update', $stakeholder->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
						@csrf
						@method('PUT')
						<div class="form-group">
							<label for="name">Naziv / Ime:</label>
							<input type="text" class="form-control" id="name" name="name" autofocus value="{{ $stakeholder->name }}">
							@error('name')
								<span class="text-red-700 italic text-sm">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group">
							<label for="expectation">Potrebe i očekivanja zainteresovane strane:</label>
							<textarea class="form-control" id="expectation" name="expectation">{{ $stakeholder->expectation }}</textarea>
							@error('expectation')
								<span class="text-red-700 italic text-sm">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group">
							<label for="response">Odgovor preduzeća na potrebe i očekivanja:</label>
							<textarea class="form-control" id="response" name="response">{{ $stakeholder->response }}</textarea>
							@error('response')
								<span class="text-red-700 italic text-sm">{{ $message }}</span>
							@enderror
						</div>
						<button type="submit" class="btn btn-success btn-block">Izmeni</button>
					</form>
				</div>
			</div>
		</div>
	</div>

</x-app-layout>
