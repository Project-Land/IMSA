

<x-app-layout>
    <x-slot name="header">
        <div class="row">
        
        <a type="button" href="#" class="btn col-2 bg-secondary text-white">Standardi</a>
            <div class="btn-group col-2">
  <button type="button" class="btn btn-secondary dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dokumentacija
  </button>
  <div class="dropdown-menu">
    <!-- Dropdown menu links -->
    <a class="dropdown-item" href="#">Poslovnik</a>
    <a class="dropdown-item" href="#">Politike</a>
    <a class="dropdown-item" href="#">Procedure</a>
    <a class="dropdown-item" href="#">Uputstva</a>
    
  </div>
</div>

<div class="btn-group col=2">
  <button type="button" class="btn btn-secondary dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Sistemski procesi
  </button>
  <div class="dropdown-menu">
    <!-- Dropdown menu links -->
    <a class="dropdown-item" href="#">Upravljanje rizikom</a>
    <a class="dropdown-item" href="#">Interne provere</a>
    <a class="dropdown-item" href="#">Neusaglašenosti i korektivne mere</a>
    <a class="dropdown-item" href="#">Obuke</a>
    <a class="dropdown-item" href="#">Ciljevi</a>
    <a class="dropdown-item" href="#">Odobreni isporučioci</a>
    <a class="dropdown-item" href="#">Zainteresovane strane</a>
    <a class="dropdown-item" href="#">Upravljanje reklamacijama</a>
  </div>
</div>
        </div>
    </x-slot>
    
    
          

          

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome />
                @foreach($standards as $standard)
                <a href="">{{ $standard->name }}</a>
                @endforeach
            </div>
            </div>
</div>
    
</x-app-layout>

