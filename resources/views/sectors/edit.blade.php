<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sektori - Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('sectors.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('sectors.update', $sector->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="form-group">
				<label for="name">Naziv sektora:</label>
				<input type="text" class="form-control" id="name" name="name" value="{{ $sector->name }}" autofocus>
				@error('name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

</x-app-layout>
