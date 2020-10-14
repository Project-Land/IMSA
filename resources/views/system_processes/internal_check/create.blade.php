<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi dokument za Standard') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form action="{{route('internal-check.store')}}" method="POST" enctype="multipart/form-data">
			
        <div class="form-group">
            <label for="check_date">Termin provere</label>
            <input type="date" class="form-control" id="check_date" placeholder="">
        </div>

        <div class="form-group">
            <label for="sector">Područje promene</label>
            <select class="form-control" id="sector">
            <option>Prodaja</option>
            <option>Marketing</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sector">Vođe tima</label>
            <select class="form-control" id="sector">
            <option>Nikola</option>
            <option>Milos</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sector">Standard</label>
            <select class="form-control" id="sector">
            <option>9001</option>
            <option>1005</option>
            </select>
        </div>
            
        <div class="form-group">
            <label for="num_plan_ip">Broj plana IP</label>
            <input type="number" class="form-control" id="num_plan_ip">
        </div>
           
        
        <button type="submit" class="btn btn-primary">Kreairaj</button>
        </form>
    </div>

    <script>
      	document.getElementById("name_file").addEventListener("change", function(e){
        	let file = document.getElementById('name_file').files[0];
        	if(file)document.getElementById('old_document').textContent=file.name;
          });
          
          
    </script>

</x-app-layout>
