<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{session('standard_name').' - Izveštaj sa interne provere - Kreiranje'}}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="/internal-check"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li><span class="text-red-700 italic text-sm">{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif

		<form id="internal_check_report_create_form" action="{{ route('internal-check-report.store') }}" autocomplete="off" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
        
            <input type="hidden" name="internal_check_id" value="{{ $internalCheck->id }}" readonly>
            <div class="row"> 
                <div class="form-group col">
                    <label for="checked_sector">Proveravano područje</label>
                    <input type="text" class="form-control" id="checked_sector" name="checked_sector" value="{{$internalCheck->sector->name}}" readonly>
                </div>

                <div class="form-group col">
                    <label for="standard">Standard</label>
                    <input type="text" class="form-control" id="standard" name="standard" value="{{$internalCheck->standard->name}}" readonly>
                </div>

                <div class="form-group col">
                    <label for="team_for_internal_check">Tim za proveru</label>
                    <input type="text" class="form-control" id="team_for_internal_check" name="team_for_internal_check" value="{{$internalCheck->leaders}}" readonly>
                </div>
            </div>
            <div class="row">
       
                <div class="form-group col">
                    <label for="check_start">Početak provere</label>
                    <input type="text" class="form-control" id="check_start" name="check_start" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_start)) }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="check_end">Završetak provere</label>
                    <input type="text" class="form-control" id="check_end" name="check_end" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_end)) }}" readonly>
                </div>
            </div>
                <div class="row">
                <div class="form-group col">
                    <label for="specification">Specifikacija dokumenata</label>
                    <textarea rows="3" class="form-control" id="specification" name="specification"></textarea>
                    @error('specification')
                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="inconsistencies">Neusaglašenosti</label>
                    <select class="form-control" id="inconsistencies" name="inconsistencies" >
                    <option value="0">ne</option>
                    <option value="1">da</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label for="recommendations">Preporuke</label>
                    <select class="form-control" id="recommendations" name="recommendations">
                    <option value="0">ne</option>
                    <option value="1">da</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
            <button type="submit" class="btn btn-success">Izmeni</button>
             </div>
          
           

        </form>
    </div>

    <script>
    let counter=1;

    

    function removeInput(){
   
   if(this.dataset.counter=='counter'){}
   // counter--;
   if(this.dataset.counter=='coun'){}
  //  coun--;
   this.closest("div").remove();
}


    const addSecondInconsistencies=function(){
        if(document.getElementById("newInput"+(counter-1)).value==="")
        return;
        document.getElementById("button"+(counter-1)).style="display:none;";
        const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewInconsistencies=document.createElement('button');

            let deletebutton=document.createElement('button');
        deletebutton.classList="btn btn-danger";
        deletebutton.setAttribute("data-counter", "counter");
        deletebutton.id="button"+counter;
        deletebutton.innerHTML='<i class="fas fa-trash"></i>';
        deletebutton.addEventListener('click',removeInput);


            addNewInconsistencies.classList="btn btn-success";
            addNewInconsistencies.type="button";
            addNewInconsistencies.id="button"+counter;
            addNewInconsistencies.addEventListener('click',addSecondInconsistencies);
            addNewInconsistencies.textContent="Dodaj još jednu neusaglašenost"
            label.for="newInput"+counter;
            div.append(label);
            label.textContent="Upiši neusaglašenost";
            newInput.id='newInput'+counter;
            newInput.name='newInput'+counter;
            newInput.type='text';
            newInput.classList="form-control";
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputDiv"+counter;
            document.getElementById("newInputDiv"+(counter-1)).after(div);



            const div2=document.createElement('div');
            div2.innerHTML+=
            `<div style="background:#5c9c6a;padding:10px;">
            <h2>Popuni karton korektivne mere</h2>
			<div class="form-group">
				<label for="noncompliance_source[${counter}]">Izvor informacije o neusaglašenostima:</label>


                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-3 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="" require>
					<option value="">Izaberi...</option>
					<option value="Eksterna provera">Eksterna provera</option>
					<option value="Interna provera" >Interna provera</option>
					<option value="Preispitivanje ISM-a" >Preispitivanje ISM-a</option>
					<option value="Žalba" >Žalba</option>
					<option value="Ostalo" >Ostalo</option>
				</select>
			
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


            div.append(addNewInconsistencies);
            div.append(deletebutton);
            newInput.focus();
            counter++;
    }
    const form=document.getElementById('internal_check_report_create_form');
    const inconsistencies=document.getElementById('inconsistencies');
    const recommendations=document.getElementById('recommendations');
    

    const addInput=function(){
        let inconsistencies=document.getElementById('inconsistencies');
        const form=document.getElementById('internal_check_report_create_form');


        let deletebutton=document.createElement('button');
        deletebutton.classList="btn btn-danger";
        deletebutton.setAttribute("data-counter", "counter");
        deletebutton.id="button"+counter;
        deletebutton.innerHTML='<i class="fas fa-trash"></i>';
        deletebutton.addEventListener('click',removeInput);


        
        if(inconsistencies.value==1){
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
          
            const label=document.createElement('label');
            const addNewInconsistencies=document.createElement('button');
            addNewInconsistencies.classList="btn btn-success";
            addNewInconsistencies.type="button";
            addNewInconsistencies.id="button"+counter;
            addNewInconsistencies.addEventListener('click',addSecondInconsistencies);
            addNewInconsistencies.textContent="Dodaj još jednu neusaglašenost"
            label.for="newInput"+counter;
            div.append(label);
            label.textContent="Upiši neusaglašenost";
            newInput.id='newInput'+counter;
            newInput.name='newInput'+counter;
            newInput.type='text';
            newInput.classList="form-control";
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputDiv"+counter;



            const div2=document.createElement('div');
            div2.innerHTML+=
            `<div style="background:#5c9c6a;padding:10px;">
            <h2>Popuni karton korektivne mere</h2>
			<div class="form-group">
				<label for="noncompliance_source[${counter}]">Izvor informacije o neusaglašenostima:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-3 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="" require>
					<option value="">Izaberi...</option>
					<option value="Eksterna provera">Eksterna provera</option>
					<option value="Interna provera" >Interna provera</option>
					<option value="Preispitivanje ISM-a" >Preispitivanje ISM-a</option>
					<option value="Žalba" >Žalba</option>
					<option value="Ostalo" >Ostalo</option>
				</select>
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



            
            inconsistencies.after(div);
            div.append(addNewInconsistencies);
            div.append(deletebutton);
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
        
        }else{
            let newInputs=document.querySelectorAll("[id^='newInputDiv']");
            for(let input of newInputs){
                input.remove();
                counter=1;
            }
        }

    }












let coun=1;

    const addSecondRecommedation=function(){
        if(document.getElementById("newInputRecommendation"+(coun-1)).value==="")
        return;
        document.getElementById("buttonRecommedations"+(coun-1)).style="display:none;";
        const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewRecommendations=document.createElement('button');
            addNewRecommendations.classList="btn btn-success";
            addNewRecommendations.type="button";
            addNewRecommendations.id="buttonRecommedations"+coun;
            addNewRecommendations.addEventListener('click',addSecondRecommedation);
            addNewRecommendations.textContent="Dodaj još jednu preporuku"
            document.getElementById('buttonRecommedations'+(coun-1)).remove();

            const deletebutton=document.createElement('button');
            deletebutton.classList="btn btn-danger";
            deletebutton.setAttribute("data-counter", "coun");
            deletebutton.id="buttonRecommedations"+coun;
            deletebutton.innerHTML='<i class="fas fa-trash"></i>';
            deletebutton.addEventListener('click',removeInput);


            label.for="newInputRecommendation"+coun;
            div.append(label);
            label.textContent="Upiši preporuku";
            newInput.id='newInputRecommendation'+coun;
            newInput.name='newInputRecommendation'+coun;
            newInput.type='text';
            newInput.classList="form-control";
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputRecommendationDiv"+coun;
            document.getElementById("newInputRecommendationDiv"+(coun-1)).after(div);
            div.append(addNewRecommendations);
            div.append(deletebutton);
            
            coun++;
            newInput.focus();
    }
    

    const addInputRecommedation=function(){
       
        if(recommendations.value==1){
           
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewRecommendations=document.createElement('button');
           
            addNewRecommendations.classList="btn btn-success";
            addNewRecommendations.type="button";
            addNewRecommendations.id="buttonRecommedations"+coun;
            addNewRecommendations.addEventListener('click',addSecondRecommedation);
            addNewRecommendations.textContent="Dodaj još jednu preporuku"


            const deletebutton=document.createElement('button');
            deletebutton.classList="btn btn-danger";
            deletebutton.setAttribute("data-counter", "coun");
            deletebutton.id="buttonRecommedations"+coun;
            deletebutton.innerHTML='<i class="fas fa-trash"></i>';
            deletebutton.addEventListener('click',removeInput);


            label.for="newInputRecommendation"+coun;
            div.append(label);
            label.textContent="Upiši preporuku";
            newInput.id='newInputRecommendation'+coun;
            newInput.name='newInputRecommendation'+coun;
            newInput.type='text';
            newInput.classList="form-control";
            div.append(newInput);
            div.classList="form-group mt-3";
            div.id="newInputRecommendationDiv"+coun;
            recommendations.after(div);
            div.append(addNewRecommendations);
            div.append(deletebutton);
            
            coun++;
            newInput.focus();
        
        }else{
            let newInputs=document.querySelectorAll("[id^='newInputRecommendationDiv']");
            for(let input of newInputs){
                input.remove();
                coun=1;
            }
        }

    }

  
    inconsistencies.addEventListener('change', addInput);
    recommendations.addEventListener('change', addInputRecommedation);

        
    </script>

</x-app-layout>