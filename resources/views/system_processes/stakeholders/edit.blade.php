<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Zainteresovane strane') }}  - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('stakeholders.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('stakeholders.update', $stakeholder->id) }}" method="POST">
			@csrf
            @method('PUT')
            <div class="form-group">
				<label for="name">Naziv / Ime:</label>
				<input type="text" class="form-control" id="name" name="name" autofocus value="{{ $stakeholder->name }}">
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="expectation">Potrebe i očekivanja zainteresovane strane:</label>
                <textarea class="form-control" id="expectation" name="expectation">{{ $stakeholder->expectation }}</textarea>
				@error('expectation')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="response">Odgovor preduzeća na potrebe i očekivanja:</label>
                <textarea class="form-control" id="response" name="response">{{ $stakeholder->response }}</textarea>
				@error('response')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>
