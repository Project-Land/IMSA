<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
		{{session("standard_name").' - '.$doc_type.' - Kreiranje'}}
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
			@isset($method)
				@method('{{ $method }}')
			@endisset

			<div class="mb-4">
				<label for="dokument_name" class="block text-gray-700 text-sm font-bold mb-2">Naziv dokumenta:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="document_name" name="document_name" value="{{ old('document_name') }}" autofocus>
				@error('document_name')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="version" class="block text-gray-700 text-sm font-bold mb-2">Verzija:</label>
				<input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="version" name="version" value="{{ old('version') }}" >
				@error('version')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>

			@if(isset($category) && $category == 'procedures')
			<div class="mb-4">
				<label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">Izaberi sektor</label>
				<select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="sector_id" id="sector_id">
					<option value="">Izaberi...</option>
					@foreach($sectors as $sector)
						<option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? "selected" : "" }} >{{ $sector->name }}</option>
					@endforeach
				</select>
				@error('sector_id')
					<span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div>
			@endif

			<div class="mb-4">
				<label for="name_file" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
					<svg class="w-6 h-6 mx-auto" fill="green" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
						<path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
					</svg>
					<small>Izaberi fajl</small>
				</label>
				<input type="file" class="form-control-file" id="name_file" name="file" style="display: none;">
				<span id="old_document" class="font-italic text-s">Fajl nije izabran</span>
				@error('file')
					<br><span class="text-red-700 italic text-sm">{{ $message }}</span>
				@enderror
			</div> 

			<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
		</form>
    </div>

    <script>
      	document.getElementById("name_file").addEventListener("change", function(e){
        	let file = document.getElementById('name_file').files[0];
        	if(file)document.getElementById('old_document').textContent=file.name;
      	});
    </script>

</x-app-layout>
