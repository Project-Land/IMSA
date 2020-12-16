<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ session("standard_name").' - '.__($doc_type). '- '.  __('Kreiranje')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="inline-flex items-center px-4 py-2 hover:no-underline bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="{{ $back }}"><i class="fas fa-arrow-left mr-1"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <form action="{{ $url }}" method="POST" enctype="multipart/form-data" class="bg-white mx-auto md:w-3/5 mt-2 md:mt-1 shadow-md rounded px-8 pt-6 pb-4 mb-4" autocomplete="off">
        @csrf

        <div class="mb-4">
            <label for="dokument_name" class="block text-gray-700 text-sm font-bold mb-2">{{__('Naziv dokumenta')}}:</label>
            <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="document_name" name="document_name" value="{{ old('document_name') }}" autofocus required oninvalid="this.setCustomValidity('Naziv nije popunjen')" oninput="this.setCustomValidity('')">
            @error('document_name')
                <span class="text-red-700 italic text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="version" class="block text-gray-700 text-sm font-bold mb-2">{{__('Verzija')}}:</label>
            <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="version" name="version" value="{{ old('version') }}" required oninvalid="this.setCustomValidity('Verzija nije popunjena')" oninput="this.setCustomValidity('')">
            @error('version')
                <span class="text-red-700 italic text-sm">{{ $message }}</span>
            @enderror
        </div>

        @if(isset($category) && ($category == 'procedures' || $category == 'forms' || $category == 'manuals'))
            <div class="mb-4">
                <label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">{{__('Izaberi sektor')}}</label>
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
            <label for="name_file" class="btn md:w-auto sm:w-full flex flex-col items-center px-8 py-1 bg-white text-blue rounded-lg shadow tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-black">
                <svg class="w-6 h-6 mx-auto" fill="blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                </svg>
                <small>{{__('Izaberi fajl')}}</small>
            </label>
            <input type="file" class="form-control-file d-none" id="name_file" name="file">
            <span class="font-italic text-s ml-2" id="old_document">{{__('Fajl nije izabran')}}</span>
            @error('file')
                <br><span class="text-red-700 italic text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end px-1 text-right sm:px-6">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">{{__('Kreiraj')}}</button>
        </div>
    </form>

    <script>
      	document.getElementById("name_file").addEventListener("change", function(e){
        	let file = document.getElementById('name_file').files[0];
			if(file)
				document.getElementById('old_document').textContent = file.name;
      	});
    </script>

</x-app-layout>
