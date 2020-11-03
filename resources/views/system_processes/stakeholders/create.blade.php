<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane') }}  - {{ __('Kreiranje') }}
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
					<form action="{{ route('stakeholders.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
						@csrf
						<div class="form-group">
							<label for="name">Naziv / Ime:</label>
							<input type="text" class="form-control rounded-0" id="name" name="name" value="{{ old('name') }}" autofocus>
							@error('name')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group">
							<label for="expectation">Potrebe i očekivanja zainteresovane strane:</label>
							<textarea class="form-control rounded-0" id="expectation" name="expectation">{{ old('expectation') }}</textarea>
							@error('expectation')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<div class="form-group">
							<label for="response">Odgovor preduzeća na potrebe i očekivanja:</label>
							<textarea class="form-control rounded-0" id="response" name="response">{{ old('response') }}</textarea>
							@error('response')
								<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
						<button type="submit" class="btn btn-success btn-block rounded-0">Kreiraj</button>
					</form>
				</div>
			</div>
		</div>
	</div>

</x-app-layout>
