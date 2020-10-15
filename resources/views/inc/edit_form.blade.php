<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Izmeni dokument za Standard') }}  {{session('standard_name')}}
        </h2>
    </x-slot>

	<div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ $back }}"><i class="fas fa-arrow-left"></i> Nazad</a>
      	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{ $url }}" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="form-group">
				<label for="dokument_name">Naziv dokumenta:</label>
        		<input type="text" class="form-control" id="document_name" name="document_name" placeholder="Naziv dokumenta" value="{{ $document->document_name }}" autofocus>
				@error('document_name')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<div class="form-group">
				<label for="document_version">Verzija:</label>
				<input type="text" class="form-control" id="document_version" name="document_version" placeholder="Verzija" value="{{ $document->version }}">
				@error('document_version')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			@if(isset($category) && $category == 'procedures')
			<div class="form-group">
				<label for="sector">Izaberi sektor</label>
				<select class="form-control" name="sector" id="sector">
					<option value="">Izaberi...</option>
					@foreach($sectors as $sector)
						<option value="{{ $sector->id }}" {{ $document->sector_id == $sector->id ? "selected" : "" }} >{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector')
					<span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			@endif
			<div class="form-group">
				<label for="name_file" class="btn btn-primary">Izaberi Fajl</label>
				<input type="file" class="form-control-file" id="name_file" name="file" style="display:none;">
				<span id="old_document">{{$document->file_name}}</span>
				@error('file')
					<br><span class="text-danger">{{ $message }}</span>
				@enderror
			</div>
			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

	<script>
		document.getElementById("name_file").addEventListener("change", function(e){
			let file = document.getElementById('name_file').files[0];
			if(file)document.getElementById('old_document').textContent=file.name;
		});
	</script>

</x-app-layout>