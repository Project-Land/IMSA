<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Izveštaj sa interne provere ') }} {{session('standard_name')}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto w-75 mt-10 bg-secondary p-10 rounded container">

		<form id="internal_check_report_edit_form" action="{{route('internal-check-report.update',$internalCheckReport->id)}}" method="POST">
            @csrf
            @method('PUT')
        <div class="row">
        <div class="form-group col" >
            <label for="checked_sector">Proveravano područje</label>
            <input type="text" class="form-control" id="checked_sector" placeholder="" name="checked_sector" value="{{$internalCheckReport->internalCheck->sector}}" readonly>
           
        </div>

        <div class="form-group col">
            <label for="standard">Standard</label>
            <input type="text" class="form-control" id="standard" placeholder="" name="standard" value="{{$internalCheckReport->internalCheck->standard->name}}" readonly>
        </div>

        <div class="form-group col">
            <label for="team_for_internal_check">Tim za proveru</label>
            <input type="text" class="form-control" id="team_for_internal_check" placeholder="" name="team_for_internal_check" value="{{$internalCheckReport->internalCheck->leaders}}" readonly>
        </div>

        </div>

        <div class="row">
        <div class="form-group col">
            <label for="check_start">Početak provere</label>
            <input type="text" class="form-control" id="check_start" placeholder="" name="check_start" value="{{$internalCheckReport->internalCheck->planIp->check_start}}" readonly>
        </div>

        <div class="form-group col">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" placeholder="" name="check_end" value="{{$internalCheckReport->internalCheck->planIp->check_end}}" readonly>
        </div>

        <div class="form-group col">
            <label for="specification">Specifikacija dokumenata</label>
            <input type="text" class="form-control" id="specification" placeholder="" name="specification" value="{{$internalCheckReport->specification}}">
        </div>
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
           <a href="{{route('corrective-measures.edit',$inc->correctiveMeasure->id)}}" >Korektivna mera</a>
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
            div.innerHTML+= `<div style="background:#5c9c6a;padding:10px;">
            <h2>Popuni karton korektivne mere</h2>
			<div class="form-group">
				<label for="noncompliance_source">Izvor informacije o neusaglašenostima:</label>
				<input type="text" class="form-control" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="">
			
			</div>
			<div class="form-group">
				<label for="noncompliance_description">Opis neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_description[${counter}]" name="noncompliance_description[${counter}]"></textarea>
				
			</div>
			<div class="form-group">
				<label for="noncompliance_cause">Uzrok neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_cause[${counter}]" name="noncompliance_cause[${counter}]"></textarea>
			
			</div>
			<div class="form-group">
				<label for="measure">Mera za otklanjanje neusaglašenosti:</label>
				<textarea class="form-control" id="measure[${counter}]" name="measure[${counter}]"></textarea>
				
			</div>
			<div class="form-group">
				<label for="measure_approval">Odobravanje mere:</label>
				<select class="form-control" name="measure_approval[${counter}]" id="measure_approval">
					<option value="1" >DA</option>
					<option value="0" >NE</option>
				</select>
			</div>
			<div class="form-group" id="measure_reason_field" style="display: none">
				<label for="measure_approval_reason">Razlog neodobravanja mere</label>
				<input type="text" class="form-control" name="measure_approval_reason[${counter}]" id="measure_approval_reason">
			</div>
			<div class="form-group">
				<label for="measure_status">Status mere:</label>
				<select class="form-control" name="measure_status[${counter}]" id="measure_status">
					<option value="0" >NE</option>
					<option value="1"  >DA</option>
				</select>
			</div>
			<div class="form-group" id="measure_effective_field" style="display: none">
				<label for="measure_effective">Mera efektivna:</label>
				<select class="form-control" name="measure_effective[${counter}]" id="measure_effective">
					<option value="">Izaberi...</option>
					<option value="1"  >DA</option>
					<option value="0">NE</option>
				</select>
			</div>
            </div>`;
            inconsistenciesDiv.append(div);
            
            counter++;

            $('#measure_approval').change( () => {
        console.log('aa');
		if($('#measure_approval').val() == 0){
			$('#measure_reason_field').css('display', '');
		}
		else{
			$('#measure_reason_field').css('display', 'none');
			$('#measure_reason').val('');
		}
	})

	$('#measure_status').change( () => {
		if($('#measure_status').val() == 1){
			$('#measure_effective_field').css('display', '');
		}
		else{
			$('#measure_effective_field').css('display', 'none');
		}
	})

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





       