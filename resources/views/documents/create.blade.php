@extends('dashboard.php')
@section('content')
<form>

<div class="form-group">
    <label for="document_name">Example label</label>
    <input type="text" class="form-control" id="document_name" placeholder="Naziv dokumenta">
  </div>
  <div class="form-group">
    <label for="version">Another label</label>
    <input type="text" class="form-control" id="version" placeholder="Verzija">
  </div>
  <div class="form-group">
    <label for="pdf_upload">Izaberite PDF</label>
    <input type="file" class="form-control-file" id="pdf_upload">
  </div>
</form>
@endsection