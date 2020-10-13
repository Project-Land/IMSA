<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }}
        </h2>
    </x-slot>

    <div class="mx-auto w-50 mt-20 bg-secondary p-10 rounded">

		<form action="{{$url}}" method="POST" enctype="multipart/form-data">
			@csrf
			@isset($method)
			@method('{{$method}}')
			@endisset
			<div class="form-group">
				<label for="dokument_name">Naziv dokumenta:</label>
				<input type="text" class="form-control" id="document_name" name="document_name" placeholder="Naziv dokumenta" value="{{old('document_name')}}" autofocus>
			</div>
			<div class="form-group">
				<label for="document_version">Verzija:</label>
				<input type="text" class="form-control" id="document_version" name="document_version" placeholder="Verzija" value="{{old('document_version')}}">
			</div>
			<div class="form-group">
				<label for="name_file">Izaberi PDF Fajl</label>
				<input type="file" class="form-control-file" id="name_file" name="file">
			</div>
			<button type="submit" class="btn btn-primary">Kreiraj</button>
		</form>
    </div>

	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif


</x-app-layout>
