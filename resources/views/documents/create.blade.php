<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj poslovnik') }}
        </h2>
    </x-slot>

    <div class="mx-auto w-50 mt-20 bg-secondary p-10 rounded">
		<form method="post" action="{{ route('rules-of-procedures.store') }}" enctype="multipart/form-data">
			@csrf
			<div class="form-group">
				<label for="dokument_name">Naziv dokumenta:</label>
				<input type="text" class="form-control" name="document_name" id="document_name" placeholder="Naziv dokumenta" autofocus required>
			</div>
			<div class="form-group">
				<label for="document_version">Verzija:</label>
				<input type="text" class="form-control" name="document_version" id="document_version" placeholder="Verzija" required>
			</div>
			<div class="form-group">
				<label for="pdf_file_upload">Izaberi PDF Fajl</label>
				<input type="file" class="form-control-file" name="file" id="pdf_file_upload" required>
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
