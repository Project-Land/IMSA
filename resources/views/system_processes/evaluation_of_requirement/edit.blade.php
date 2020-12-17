<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Vrednovanje zakonskih i drugih zahteva')}} - {{__('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('evaluation-of-requirements.index') }}"><i class="fas fa-arrow-left"></i> {{__('Nazad')}}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('evaluation-of-requirements.update', $requirement->id) }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="requirement_level" class="block text-gray-700 text-sm font-bold mb-2">{{__('Nivo sa kojeg zahtev potiče')}}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="requirement_level" name="requirement_level" required oninvalid="this.setCustomValidity('{{__("Izaberite nivo")}}')" oninput="this.setCustomValidity('')">
                        <option value="">{{ __('Izaberite')}}...</option>
                        <option value="{{ __('Evropska Unija')}}" @if($requirement->requirement_level ==  __('Evropska Unija')){{ "selected" }}@endif>{{ __('Evropska Unija')}}</option>
                        <option value="{{ __('Država')}}" @if($requirement->requirement_level ==  __('Država')){{ "selected" }}@endif>{{ __('Država')}}</option>
                        <option value="{{ __('Grad')}}" @if($requirement->requirement_level ==  __('Grad')){{ "selected" }}@endif>{{ __('Grad')}}</option>
                        <option value="{{ __('Lokalna samouprava')}}" @if($requirement->requirement_level ==  __('Lokalna samouprava')){{ "selected" }}@endif>{{ __('Lokalna samouprava')}}</option>
                        <option value="{{ __('Partner')}}" @if($requirement->requirement_level ==  __('Partner')){{ "selected" }}@endif>{{ __('Partner (kupac, dobavljač...)')}}</option>
                        <option value="{{ __('Ostalo')}}" @if($requirement->requirement_level == __('Ostalo')){{ "selected" }}@endif>{{ __('Ostalo')}}</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="document_name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Naziv dokumenta/zakona, ili opis zahteva')}}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="document_name" id="document_name" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')" oninput="this.setCustomValidity('')" value="{{ $requirement->document_name }}">
                    @error('document_name')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="compliance" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Ocena usaglašenosti')}}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="compliance" name="compliance" required oninvalid="this.setCustomValidity('{{__("Izaberite ocenu usaglašenosti")}}')" oninput="this.setCustomValidity('')">
                        <option value="1" @if($requirement->compliance){{ 'selected' }}@endif>{{ __('Usaglašen')}}</option>
                        <option value="0" @if(!$requirement->compliance){{ 'selected' }}@endif>{{ __('Neusaglašen')}}</option>
                    </select>
                    @error('compliance')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="note" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Napomena')}}</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="note" id="note" value="{{ $requirement->note }}">
                    @error('note')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div id="divParent" style="background-color:#ebffe6;">
                @if($cm=$requirement->correctiveMeasures[0]??null)
                <div class="py-2 px-4">
                        <h4 class="text-center my-3">{{ __('Karton korektivne mere')}} <a href="{{ route('corrective-measures.edit', $cm->id) }}">{{$cm->name}}</a></h4>

                        <div class="form-group">
                            <label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima')}}:</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source" name="noncompliance_source" required oninvalid="this.setCustomValidity('{{__("Izaberite izvor informacije o neusaglašenosti")}}')" oninput="this.setCustomValidity('')">
                                <option value="">{{ __('Izaberi')}}...</option>
                                <option value="{{ __('Eksterna provera')}}" @if($cm->noncompliance_source ==  __('Eksterna provera')){{ 'selected' }}@endif>{{ __('Eksterna provera')}}</option>
                                <option value="{{ __('Interna provera')}}" @if($cm->noncompliance_source ==  __('Interna provera')){{ 'selected' }}@endif>{{ __('Interna provera')}}</option>
                                <option value="{{ __('Preispitivanje ISM-a')}}" @if($cm->noncompliance_source ==  __('Preispitivanje ISM-a')){{ 'selected' }}@endif>{{ __('Preispitivanje ISM-a')}}</option>
                                <option value="{{ __('Žalba')}}" @if($cm->noncompliance_source ==  __('Žalba')){{ 'selected' }}@endif>{{ __('Žalba')}}</option>
                                <option value="{{ __('Ostalo')}}" @if($cm->noncompliance_source ==  __('Ostalo')){{ 'selected' }}@endif>{{ __('Ostalo')}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti')}}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                            oninput="this.setCustomValidity('')" >{{ $cm->noncompliance_description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti')}}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                            oninput="this.setCustomValidity('')">{{ $cm->noncompliance_cause }}</textarea>

                        </div>
                        <div class="form-group">
                            <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti')}}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                            oninput="this.setCustomValidity('')">{{ $cm->measure }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje mere')}}:</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
                                <option value="1" @if($cm->measure_approval == 1){{'selected'}}@endif>{{ __('DA')}}</option>
                                <option value="0" @if($cm->measure_approval == 0){{'selected'}}@endif>{{ __('NE')}}</option>
                            </select>
                        </div>

                        <div class="form-group" id="measure_reason_field" style="@if($cm->measure_approval){{'display: none'}}@endif">
                            <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Razlog neodobravanja mere')}}</label>
                            <input oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                            oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="measure_approval_reason" id="measure_approval_reason" value="{{ $cm->measure_approval_reason }}">
                        </div>

                        <div class="form-group">
                            <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je mera sprovedena?')}}</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
                                <option value="0" @if($cm->measure_status == 0){{ 'selected' }}@endif>{{ __('NE')}}</option>
                                <option value="1" @if($cm->measure_status == 1){{' selected' }}@endif >{{ __('DA')}}</option>
                            </select>
                        </div>

                        <div class="form-group" id="measure_effective_field" style="@if(!$cm->measure_status){{ 'display: none' }}@endif">
                            <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna')}}:</label>
                            <select oninvalid="this.setCustomValidity('{{__("Izaberite efektivnost mere")}}')"
                            oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
                                <option value="">Izaberi...</option>
                                <option value="1" @if($cm->measure_effective == 1){{ 'selected' }}@endif >{{ __('DA')}}</option>
                                <option value="0" @if($cm->measure_effective == 0){{ 'selected' }}@endif>{{ __('NE')}}</option>
                            </select>
                        </div>

                    </div>
                @endif

            </div>
            <div class="mb-4 mt-1">
                <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline float-md-right">{{ __('Izmeni')}}</button>
            </div>
        </form>
    </div>

</x-app-layout>

<script>

let counter=1;
function addCorrectiveMeasure(){
   
    if(document.getElementById('noncompliance_description') && counter==1){
        return;
    } counter++;
    const compliance=document.getElementById('compliance');
    const divParent=document.getElementById('divParent');
    if(compliance.value==1){
        divParent.innerHTML = "";
        console.log(compliance.value);
        return;
    }
    const divChild=document.createElement('div');
    const div=document.getElementById('divParent');

       // const divChild = document.createElement('div');
       // const div = document.getElementById('divParent');

        divChild.innerHTML = `
            <div class="py-2 px-4">
                <h4 class="text-center my-3">{{ __('Karton korektivne mere')}}</h4>

                <div class="form-group">
                    <label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima')}}:</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source" name="noncompliance_source" value="" required oninvalid="this.setCustomValidity('{{__("Izaberite izvor informacije o neusaglašenosti")}}')"
                    oninput="this.setCustomValidity('')">
                    <option value="">{{ __('Izaberi')}}...</option>
					<option value="{{ __('Eksterna provera')}}">{{ __('Eksterna provera')}}</option>
					<option value="{{ __('Interna provera')}}">{{ __('Interna provera')}}</option>
					<option value="{{ __('Preispitivanje ISM-a')}}" >{{ __('Preispitivanje ISM-a')}}</option>
					<option value="{{ __('Žalba')}}" >{{ __('Žalba')}}</option>
					<option value="{{ __('Ostalo')}}">{{ __('Ostalo')}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti')}}:</label>
                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                    oninput="this.setCustomValidity('')" ></textarea>
                </div>

                <div class="form-group">
                    <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti')}}:</label>
                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                    oninput="this.setCustomValidity('')"></textarea>
                </div>

                <div class="form-group">
                    <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti')}}:</label>
                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                    oninput="this.setCustomValidity('')"></textarea>
                </div>

                <div class="form-group">
                    <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje mere')}}:</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
                        <option value="1">{{ __('DA')}}</option>
                        <option value="0">{{ __('NE')}}</option>
                    </select>
                </div>

                <div class="form-group" id="measure_reason_field" style="display: none">
                    <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Razlog neodobravanja mere')}}</label>
                    <input oninvalid="this.setCustomValidity('{{__("Popunite polje")}}')"
                    oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="measure_approval_reason" id="measure_approval_reason" >
                </div>

                <div class="form-group">
                    <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je mera sprovedena?')}}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
                        <option value="0">{{ __('NE')}}</option>
                        <option value="1">{{ __('DA')}}</option>
                    </select>
                </div>

                <div class="form-group" id="measure_effective_field" style="display: none">
                    <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna')}}:</label>
                    <select oninvalid="this.setCustomValidity('{{__("Izaberite efektivnost mere")}}')"
                    oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
                        <option value="">{{ __('Izaberi')}}...</option>
                        <option value="1">{{ __('DA')}}</option>
                        <option value="0">{{ __('NE')}}</option>
                    </select>
                </div>
                </div>
               `;


            div.append(divChild);

            document.getElementById('noncompliance_description').focus();

                let id=`#measure_approval`;
                let id_ms=`#measure_status`;
                let id_mef=`#measure_effective_field`;
                let id_mrf=`#measure_reason_field`;
                let id_mar=`#measure_approval_reason`;
                let id_me=`#measure_effective`;

                $(id).change( () => {
                    if($(id).val() == 0){
                        $(id_mrf).css('display', '');
                        $(id_mar).attr('required', true);
                    }
                    else{
                        $(id_mrf).css('display', 'none');
                        $(id_mar).val('');
                        $(id_mar).attr('required', false);
                    }
                })

                $(id_ms).change( () => {
                    if($(id_ms).val() == 1){
                        $(id_mef).css('display', '');
                        $(id_me).attr('required', true);
                    }
                    else{
                        $(id_mef).css('display', 'none');
                        $(id_me).attr('required', false);
                    }
                }) 
               // counter++;

}



let id=`#measure_approval`;
let id_ms=`#measure_status`;
let id_mef=`#measure_effective_field`;
let id_mrf=`#measure_reason_field`;
let id_mar=`#measure_approval_reason`;
let id_me=`#measure_effective`;

$(id).change( () => {
    if($(id).val() == 0){
        $(id_mrf).css('display', '');
        $(id_mar).attr('required', true);
    }
    else{
        $(id_mrf).css('display', 'none');
        $(id_mar).val('');
        $(id_mar).attr('required', false);
    }
})

$(id_ms).change( () => {
    if($(id_ms).val() == 1){
        $(id_mef).css('display', '');
        $(id_me).attr('required', true);
    }
    else{
        $(id_mef).css('display', 'none');
        $(id_me).attr('required', false);
    }
})


    document.getElementById('compliance').addEventListener('change', addCorrectiveMeasure);
</script>
