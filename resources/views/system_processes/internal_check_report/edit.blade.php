<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj novi Izveštaj sa interne provere ') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-50 mt-10 bg-secondary p-10 rounded">

		<form id="internal_check_report_edit_form" action="{{route('internal-check-report.update',$internalCheckReport->id)}}" method="POST">
            @csrf
            @method('PUT')
           
        <div class="form-group">
            <label for="checked_sector">Proveravano područje</label>
            <input type="text" class="form-control" id="checked_sector" placeholder="" name="checked_sector" value="{{$internalCheckReport->internalCheck->sector}}" readonly>
        </div>

        <div class="form-group">
            <label for="standard">Standard</label>
            <input type="text" class="form-control" id="standard" placeholder="" name="standard" value="{{$internalCheckReport->internalCheck->standard->name}}" readonly>
        </div>

        <div class="form-group">
            <label for="team_for_internal_check">Tim za proveru</label>
            <input type="text" class="form-control" id="team_for_internal_check" placeholder="" name="team_for_internal_check" value="{{$internalCheckReport->internalCheck->leaders}}" readonly>
        </div>

    
       
        <div class="form-group">
            <label for="check_start">Početak provere</label>
            <input type="text" class="form-control" id="check_start" placeholder="" name="check_start" value="{{$internalCheckReport->internalCheck->planIp->check_start}}" readonly>
        </div>

        <div class="form-group">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" placeholder="" name="check_end" value="{{$internalCheckReport->internalCheck->planIp->check_end}}" readonly>
        </div>

        <div class="form-group">
            <label for="specification">Specifikacija dokumenata</label>
            <input type="text" class="form-control" id="specification" placeholder="" name="specification" value="{{$internalCheckReport->specification}}">
        </div>

        <div class="form-group">
            <span id="addInconsistencies"  class="btn btn-primary">Dodaj neusaglašenost</span>
            <span id="addRecommendations"  class="btn btn-primary">Dodaj preporuku</span>
           
        </div>
<div id="inconsistenciesDiv">
        @foreach($internalCheckReport->inconsistencies as $inc)
        <div class="form-group">
       
            <label for="inconsistencies">Neusaglašenost</label>
           <textarea class="form-control" name="inconsistencies[{{$inc->id}}]">{{$inc->description}}</textarea>
           <button class="deleteButton btn btn-danger"><i class="fas fa-trash"></i></button>
            </div>
        
        @endforeach
</div>

       
<div id="recommendationsDiv" >
        @foreach($internalCheckReport->recommendations as $rec)
        <div class="form-group" id="recommendations[{{$rec->id}}]">
            <label for="recommendations">Preporuka</label>
           <textarea class="form-control" name="recommendations[{{$rec->id}}]">{{$rec->description}}</textarea>
          <button class="deleteButton btn btn-danger"><i class="fas fa-trash"></i></button>
          
        </div>
        @endforeach
</div>
            
        <button type="submit" id="submitForm" class="btn btn-primary" >Izmeni</button>
        </form>
    </div>

    <script>
    const form=document.getElementById('internal_check_report_edit_form');
    const inconsistencies=document.getElementById('addInconsistencies');
    const recommendations=document.getElementById('addRecommendations');
    const recommendationsDiv=document.getElementById('recommendationsDiv');
    const inconsistenciesDiv=document.getElementById('inconsistenciesDiv');
    let counter=1;
    let coun=1;

    const addInput=function(){
        
        const form=document.getElementById('internal_check_report_update_form');
       
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewInconsistencies=document.createElement('span');
            addNewInconsistencies.classList="btn btn-danger";
           
            addNewInconsistencies.id="button"+counter;
           
            addNewInconsistencies.innerHTML='<i class="fas fa-trash"></i>';
            label.for="newInput"+counter;
            div.append(label);
            label.textContent="Upiši neusaglašenost";
            newInput.id='newInput'+counter;
            newInput.name='newInput'+counter;
            newInput.type='text';
            newInput.classList="form-control";
            addNewInconsistencies.addEventListener('click',removeInput);
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputDiv"+counter;
            inconsistencies.after(div);
            div.append(addNewInconsistencies);
            inconsistenciesDiv.append(div);
            
            counter++;
    }


    const addInputRecommedation=function(){
       
       
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewRecommendations=document.createElement('span');
            addNewRecommendations.classList="btn btn-danger";
           
            addNewRecommendations.id="buttonRecommedations"+coun;
           
            addNewRecommendations.innerHTML='<i class="fas fa-trash"></i>';
            label.for="newInputRecommendation"+coun;
            div.append(label);
            label.textContent="Upiši preporuku";
            newInput.id='newInputRecommendation'+coun;
            newInput.name='newInputRecommendation'+coun;
            newInput.type='text';
            newInput.classList="form-control";
            addNewRecommendations.addEventListener('click',removeInput);
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputRecommendationDiv"+coun;
            recommendations.after(div);
            div.append(addNewRecommendations);
            recommendationsDiv.append(div);
            
            coun++;
        
    }
function removeInput(){
  
   this.closest("div").remove();
}

    inconsistencies.addEventListener('click', addInput);
    recommendations.addEventListener('click', addInputRecommedation);
    document.querySelectorAll('.deleteButton').forEach(button=>{
        button.addEventListener('click',removeInput);
    });
   

    </script>

</x-app-layout>



