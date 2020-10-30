<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Izveštaj sa interne provere ') }} 
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>
  
    <div class="mx-auto w-75 mt-10 p-10 rounded container border" style="background:#f0f9f0;">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
		<form id="internal_check_report_edit_form" action="{{route('internal-check-report.update',$internalCheckReport->id)}}" method="POST">
            @csrf
            @method('PUT')
        <div class="row">
        <div class="form-group col" >
            <label for="checked_sector">Proveravano područje</label>
            <input type="text" class="form-control" id="checked_sector" placeholder="" name="checked_sector" value="{{$internalCheckReport->internalCheck->sector->name}}" readonly>
           
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
            <input type="text" class="form-control" id="check_start" placeholder="" name="check_start" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_start)) }}" readonly>
        </div>

        <div class="form-group col">
            <label for="check_end">Završetak provere</label>
            <input type="text" class="form-control" id="check_end" placeholder="" name="check_end" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_end)) }}" readonly>
        </div>

        <div class="form-group col">
            <label for="specification">Specifikacija dokumenata</label>
            <input type="text" class="form-control" id="specification" placeholder="" name="specification" value="{{$internalCheckReport->specification}}" required>
            @error('specification')
					<span class="text-danger">{{ $message }}</span>
			@enderror
        </div>
        </div>


        <div class="form-group mt-2">
            <span id="addInconsistencies"  class="btn btn-success">Dodaj neusaglašenost</span>
            <span id="addRecommendations"  class="btn btn-success">Dodaj preporuku</span>
        </div>
        
        <div id="inconsistenciesDiv" class="row border-top mt-2">
            @foreach($internalCheckReport->inconsistencies as $inc)
            <div class="form-group col-6 mt-3">
        
                <label for="inconsistencies">Neusaglašenost</label>
            <textarea class="form-control" name="inconsistencies[{{$inc->id}}]" required>{{$inc->description}}</textarea>
            @error('inconsistencies.'.$inc->id)
					<span class="text-danger">{{ $message }}</span>
			@enderror
            <button class="deleteButton btn btn-danger"><i class="fas fa-trash"></i></button>
            <a href="{{route('corrective-measures.edit',$inc->correctiveMeasure->id)}}" >Korektivna mera</a>
                </div>
    
            @endforeach
        </div>
        

       
        <div id="recommendationsDiv"  class="row border-top mt-2">
        
                @foreach($internalCheckReport->recommendations as $rec)
                <div class="form-group col-6 mt-3" id="recommendations[{{$rec->id}}]">
                    <label for="recommendations">Preporuka</label>
                <textarea class="form-control" name="recommendations[{{$rec->id}}]" required>{{$rec->description}}</textarea>
                @error('recommendations.'.$rec->id)
					<span class="text-danger">{{ $message }}</span>
			    @enderror
                <button class="deleteButton btn btn-danger"><i class="fas fa-trash"></i></button>
                
                </div>
                @endforeach
        </div>
            
        <button type="submit" id="submitForm" class="btn btn-success mt-5" >Izmeni</button>
        </form>
    </div>


   
    <script>


let counter=1;
let coun=1;

const els=document.querySelector('#inconsistenciesDiv');
   let childs=els.childElementCount;
   if(childs %2 !== 0){
    let e=els.lastElementChild;
    e.style="margin-right:10px;";
   }

function removeInput(){
   
   if(this.dataset.counter=='counter'){}
   // counter--;
   if(this.dataset.counter=='coun'){}
   // coun--;
   this.closest("div").remove();
 //  let els=document.querySelector('#inconsistenciesDiv');
 //  let childs=els.childElementCount;
 //  if(childs %2 !== 0){
//    let e=els.lastElementChild;
 //   e.style="margin-right:10px;";
  // } 
}

    const form=document.getElementById('internal_check_report_edit_form');
    const inconsistencies=document.getElementById('addInconsistencies');
    const recommendations=document.getElementById('addRecommendations');
    const recommendationsDiv=document.getElementById('recommendationsDiv');
    const inconsistenciesDiv=document.getElementById('inconsistenciesDiv');
    

    const addInput=function(){

        if(document.getElementById("newInput"+(counter-1))!=null){
            if(document.getElementById("newInput"+(counter-1)).value==="")
                return;
        }
        
        const form=document.getElementById('internal_check_report_update_form');
       
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            let addNewInconsistencies=document.createElement('button');
            addNewInconsistencies.classList="btn btn-danger";
            addNewInconsistencies.setAttribute("data-counter", "counter");
            addNewInconsistencies.id="button"+counter;
            addNewInconsistencies.innerHTML='<i class="fas fa-trash"></i>';
            label.for="newInput"+counter;
            div.append(label);
            label.textContent="Upiši neusaglašenost";
            label.classList="mt-3";
            newInput.id='newInput'+counter;
            newInput.name='newInput'+counter;
            newInput.type='text';
            newInput.style="background:#dbffe5;"
            newInput.required = true;
            newInput.classList="form-control";
            addNewInconsistencies.addEventListener('click',removeInput);
            div.append(newInput);	 
            div.classList="form-group col-6";
            div.id="newInputDiv"+counter;
            inconsistencies.after(div);
            div.append(addNewInconsistencies);
            const div2=document.createElement('div');
            div2.innerHTML+=
            `<div style="background:#5c9c6a;padding:10px;">
            <h2>Popuni karton korektivne mere</h2>
			<div class="form-group">
				<label for="noncompliance_source">Izvor informacije o neusaglašenostima:</label>
				<input type="text" class="form-control" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="" require>
			
			</div>
			<div class="form-group">
				<label for="noncompliance_description">Opis neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_description[${counter}]" name="noncompliance_description[${counter}]" require></textarea>
				
			</div>
			<div class="form-group">
				<label for="noncompliance_cause">Uzrok neusaglašenosti:</label>
				<textarea class="form-control" id="noncompliance_cause[${counter}]" name="noncompliance_cause[${counter}]" require></textarea>
			
			</div>
			<div class="form-group">
				<label for="measure">Mera za otklanjanje neusaglašenosti:</label>
				<textarea class="form-control" id="measure[${counter}]" name="measure[${counter}]" require></textarea>
				
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
				<input type="text" class="form-control" name="measure_approval_reason[${counter}]" id="measure_approval_reason" require>
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
            div.append(div2);
            inconsistenciesDiv.append(div);
            newInput.focus();
            counter++;

            $('#measure_approval').change( () => {
        
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
        if(document.getElementById("newInputRecommendation"+(coun-1))!=null){
            if(document.getElementById("newInputRecommendation"+(coun-1)).value==="")
                return;
        }
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewRecommendations=document.createElement('button');
            addNewRecommendations.classList="btn btn-danger";
            addNewRecommendations.setAttribute("data-counter", "coun");
            addNewRecommendations.id="buttonRecommedations"+coun;
           
            addNewRecommendations.innerHTML='<i class="fas fa-trash"></i>';
            label.for="newInputRecommendation"+coun;
            div.append(label);
            label.textContent="Upiši preporuku";
            label.classList="mt-3";
            newInput.id='newInputRecommendation'+coun;
            newInput.name='newInputRecommendation'+coun;
            newInput.type='text';
            newInput.style="background:#dbffe5;"
            newInput.required = true;
            newInput.classList="form-control";
            addNewRecommendations.addEventListener('click',removeInput);
            div.append(newInput);
            div.classList="form-group col-6";
            div.id="newInputRecommendationDiv"+coun;
            recommendations.after(div);
            div.append(addNewRecommendations);
            recommendationsDiv.append(div);
            newInput.focus();
            coun++;
        
    }



    inconsistencies.addEventListener('click', addInput);
    recommendations.addEventListener('click', addInputRecommedation);
    document.querySelectorAll('.deleteButton').forEach(button=>{
        button.addEventListener('click',removeInput);
    });
   
  
	



   
    </script>

</x-app-layout>





       