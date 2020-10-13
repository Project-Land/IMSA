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
        @error('document_name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
			</div>
			<div class="form-group">
				<label for="document_version">Verzija:</label>
        <input type="text" class="form-control" id="document_version" name="document_version" placeholder="Verzija" value="{{old('document_version')}}">
        @error('document_version')
        <span class="text-danger">{{ $message }}</span>
        @enderror
			</div>
			<div class="form-group">
				<label for="name_file" class="btn btn-primary">Izaberi PDF Fajl</label>
        <input type="file" class="form-control-file" id="name_file" name="file" style="display:none;">
        <span id="old_document">fajl nije izabran</span>
        @error('file')
          <span class="text-danger">{{ $message }}</span>
        @enderror
			</div>
			<button type="submit" class="btn btn-success">Kreiraj</button>
		</form>
    </div>

    <script>
    document.getElementById("name_file").addEventListener("change", function(e){
        let file = document.getElementById('name_file').files[0];
        if(file)document.getElementById('old_document').textContent=file.name;
    });
    
</script>

</x-app-layout>
