<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{session("standard_name").' - '.$doc_type.' - Izmena'}}
        </h2>
    </x-slot>

	<div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ $back }}"><i class="fas fa-arrow-left"></i> Nazad</a>
      	</div>
    </div>

    <div class="mx-auto md:w-3/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ $url }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" autocomplete="off">
			@csrf
			@method('PUT')
			<div class="mb-4">
				<label for="dokument_name">Naziv dokumenta:</label>
        		<input type="text" class="form-control" id="document_name" name="document_name" value="{{ $document->document_name }}" autofocus>
				@error('document_name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			<div class="mb-4">
				<label for="version">Verzija:</label>
				<input type="text" class="form-control" id="version" name="version" value="{{ $document->version }}" >
				@error('version')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			@if(isset($category) && $category == 'procedures')
			<div class="mb-4">
				<label for="sector_id">Izaberi sektor</label>
				<select class="form-control" name="sector_id" id="sector_id">
					<option value="">Izaberi...</option>
					@foreach($sectors as $sector)
						<option value="{{ $sector->id }}" {{ $document->sector_id == $sector->id ? "selected" : "" }} >{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector_id')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			@endif


			
			<div class="mb-4">
				<label for="name_file" class="btn text-xs md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
				<svg class="w-6 h-6 mx-auto" fill="green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
							<path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
						</svg>
						<small>Izaberi fajl</small>
						
				</label>
				<input type="file" class="form-control-file" id="name_file" name="file" style="display:none;">
				<span id="old_document">{{$document->file_name}}</span>
				@error('file')
					<br><span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			
			<!--
			<div class="mb-4">
				<div class="flex w-full items-start justify-start bg-grey-lighter">
					<label class="md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
						<svg class="w-6 h-6" fill="green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
							<path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
						</svg>
						<span class="mt-1 text-xs" id="old_document">Izaberi Fajl</span>
						<input type='file' name="file" id="name_file" class="hidden" />
					</label>
				</div>
				@error('file')
					<br><span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			-->


			<button type="submit" class="btn btn-success">Izmeni</button>
		</form>
    </div>

	<script>
		document.getElementById("name_file").addEventListener("change", function(e){
			let file = document.getElementById('name_file').files[0];
			if(file)
				document.getElementById('old_document').textContent=file.name;
		});
	</script>

</x-app-layout>