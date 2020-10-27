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

		<form id="internal_check_report_create_form" action="{{ route('internal-check-report.store') }}" method="POST">
            @csrf
        
            <input type="hidden" name="internal_check_id" value="{{ $internalCheck->id }}" readonly>

            <div class="form-group">
                <label for="checked_sector">Proveravano područje</label>
                <input type="text" class="form-control" id="checked_sector" name="checked_sector" value="{{$internalCheck->sector}}" readonly>
            </div>

            <div class="form-group">
                <label for="standard">Standard</label>
                <input type="text" class="form-control" id="standard" name="standard" value="{{$internalCheck->standard->name}}" readonly>
            </div>

            <div class="form-group">
                <label for="team_for_internal_check">Tim za proveru</label>
                <input type="text" class="form-control" id="team_for_internal_check" name="team_for_internal_check" value="{{$internalCheck->leaders}}" readonly>
            </div>
       
            <div class="form-group">
                <label for="check_start">Početak provere</label>
                <input type="text" class="form-control" id="check_start" name="check_start" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_start)) }}" readonly>
            </div>

            <div class="form-group">
                <label for="check_end">Završetak provere</label>
                <input type="text" class="form-control" id="check_end" name="check_end" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_end)) }}" readonly>
            </div>

            <div class="form-group">
                <label for="specification">Specifikacija dokumenata</label>
                <input type="text" class="form-control" id="specification" name="specification">
            </div>

            <div class="form-group">
                <label for="inconsistencies">Neusaglašenosti</label>
                <select class="form-control" id="inconsistencies" name="inconsistencies" >
                <option value="0">ne</option>
                <option value="1">da</option>
                </select>
            </div>

            <div class="form-group">
                <label for="recommendations">Preporuke</label>
                <select class="form-control" id="recommendations" name="recommendations">
                <option value="0">ne</option>
                <option value="1">da</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Izmeni</button>
        </form>
    </div>

    <script>
    let counter=1;
    const addSecondInconsistencies=function(){
        if(document.getElementById("newInput"+(counter-1)).value==="")
        return;
        document.getElementById("button"+(counter-1)).style="display:none;";
        const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewInconsistencies=document.createElement('button');
            addNewInconsistencies.classList="btn btn-primary";
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
            div.append(addNewInconsistencies);
            
            counter++;
    }
    const form=document.getElementById('internal_check_report_create_form');
    const inconsistencies=document.getElementById('inconsistencies');
    const recommendations=document.getElementById('recommendations');

    const addInput=function(){
        let inconsistencies=document.getElementById('inconsistencies');
        const form=document.getElementById('internal_check_report_create_form');
        if(inconsistencies.value==1){
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewInconsistencies=document.createElement('button');
            addNewInconsistencies.classList="btn btn-primary";
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
            inconsistencies.after(div);
            div.append(addNewInconsistencies);
            
            counter++;
        
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
            addNewRecommendations.classList="btn btn-primary";
            addNewRecommendations.type="button";
            addNewRecommendations.id="buttonRecommedations"+coun;
            addNewRecommendations.addEventListener('click',addSecondRecommedation);
            addNewRecommendations.textContent="Dodaj još jednu preporuku"
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
            
            coun++;
    }
    

    const addInputRecommedation=function(){
       
        if(recommendations.value==1){
            const newInput=document.createElement('textarea');
            const div=document.createElement('div');
            const label=document.createElement('label');
            const addNewRecommendations=document.createElement('button');
            addNewRecommendations.classList="btn btn-primary";
            addNewRecommendations.type="button";
            addNewRecommendations.id="buttonRecommedations"+coun;
            addNewRecommendations.addEventListener('click',addSecondRecommedation);
            addNewRecommendations.textContent="Dodaj još jednu preporuku"
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
            
            coun++;
        
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