<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi dokument za Standard') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href=""><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-75 mt-10 bg-secondary p-10 rounded">

		<form action="{{ route('goals.store') }}" method="POST" enctype="multipart/form-data">
			
			
			
        <div class="form-row">
        <div class="form-group col-md-6">
                <label for="year">Godina</label>
                <select class="form-control" id="year">
                    @foreach(range(date("Y"),2035) as $year)
                        <option>{{$year}}</option>
                    @endforeach
               
                </select>
            </div>

            
            <div class="form-group col-md-6">
            <label for="responsibility">Odgovornost</label>
            <input type="text" class="form-control" id="responsibility" placeholder="">
            </div>
        </div>

        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="goal">Cilj</label>
            <input type="text" class="form-control" id="goal" placeholder="">
        </div>
        <div class="form-group col-md-6">
            <label for="deadline">Rok</label>
            <input type="date" class="form-control" id="deadline" placeholder="">
        </div>
        </div>

        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="kpi">KPI</label>
            <input type="text" class="form-control" id="kpi" placeholder="">
        </div>
        <div class="form-group col-md-6">
            <label for="resources">Resursi</label>
            <input type="text" class="form-control" id="resources" placeholder="">
        </div>
        </div>

        
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="activities">Aktivnosti</label>
            <textarea rows="10" style="height:200px;" class="form-control" id="activities" placeholder=""></textarea>
        </div>

        <div class="form-group col-md-6">
            <label for="analysis">Analiza</label>
            <textarea rows="10" style="height:200px;" class="form-control" id="analysis" placeholder="" disabled></textarea>
        </div>
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
