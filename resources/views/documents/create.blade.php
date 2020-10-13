<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Standard') }}
        </h2>
    </x-slot>

    <div class="mx-auto w-50 mt-20 bg-secondary p-10 rounded">
    <form>
    <div class="form-group">
    <label for="dokument_name">Naziv dokumenta:</label>
    <input type="text" class="form-control" id="document_name" placeholder="Naziv dokumenta" autofocus>
  </div>
  <div class="form-group">
    <label for="document_version">Verzija:</label>
    <input type="text" class="form-control" id="document_version" placeholder="Verzija">
  </div>
  <div class="form-group">
    <label for="pdf_file_upload">Izaberi PDF Fajl</label>
    <input type="file" class="form-control-file" id="pdf_file_upload">
  </div>
  <button type="submit" class="btn btn-primary">Kreiraj</button>
</form>
    </div>
</x-app-layout>
