<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Vrednovanje zakonskih i drugih zahteva - Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('evaluation-of-requirements.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">

		<form action="{{ route('evaluation-of-requirements.update',$requirement->id) }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="requirement_level" class="block text-gray-700 text-sm font-bold mb-2">Nivo sa kojeg zahtev potiče</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="requirement_level" name="requirement_level" required oninvalid="this.setCustomValidity('Izaberite nivo')" oninput="this.setCustomValidity('')">
                        <option >Izaberite...</option>
                        <option value="Evropska Unija" @if($requirement->requirement_level=="Evropska Unija"){{"selected"}}@endif>Evropska Unija</option>
                        <option value="Država" @if($requirement->requirement_level=="Država"){{"selected"}}@endif>Država</option>
                        <option value="Grad" @if($requirement->requirement_level=="Grad"){{"selected"}}@endif>Grad</option>
                        <option value="Lokalna samouprava" @if($requirement->requirement_level=="Lokalna samouprava"){{"selected"}}@endif>Lokalna samouprava</option>
                        <option value="Partner" @if($requirement->requirement_level=="Partner"){{"selected"}}@endif>Partner (kupac, dobavljač...)</option>
                        <option value="Ostalo" @if($requirement->requirement_level=="Ostalo"){{"selected"}}@endif>Ostalo</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="document_name" class="block text-gray-700 text-sm font-bold mb-2">Naziv dokumenta/zakona, ili opis zahteva</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="document_name" id="document_name" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')" value="{{$requirement->document_name}}">
                    @error('document_name')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="compliance" class="block text-gray-700 text-sm font-bold mb-2">Ocena usaglašenosti</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="compliance" name="compliance" required oninvalid="this.setCustomValidity('Izaberite ocenu usaglašenosti')" oninput="this.setCustomValidity('')">
                        <option value="1" @if($requirement->compliance){{'selected'}}@endif>Usaglašen</option>
                        <option value="0" @if(!$requirement->compliance){{'selected'}}@endif>Neusaglašen</option>
                    </select>
                    @error('compliance')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="note" class="block text-gray-700 text-sm font-bold mb-2">Napomena</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="note" id="note" value="{{$requirement->note}}">
                    @error('note')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            @if($cm=$requirement->correctiveMeasures[0])
                <div class="py-2">
                    <h4 class="text-center my-3">Karton korektivne mere</h4>
                    <div class="form-group">
                        <label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">Izvor informacije o neusaglašenostima:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source" name="noncompliance_source" required oninvalid="this.setCustomValidity('Izaberite izvor informacije o neusaglašenosti')"
                        oninput="this.setCustomValidity('')">
                            <option value="">Izaberi...</option>
                            <option value="Eksterna provera" @if($cm->noncompliance_source=="Eksterna provera"){{'selected'}}@endif>Eksterna provera</option>
                            <option value="Interna provera" @if($cm->noncompliance_source=="Interna provera"){{'selected'}}@endif>Interna provera</option>
                            <option value="Preispitivanje ISM-a" @if($cm->noncompliance_source=="Preispitivanje ISM-a"){{'selected'}}@endif>Preispitivanje ISM-a</option>
                            <option value="Žalba" @if($cm->noncompliance_source=="Žalba"){{'selected'}}@endif>Žalba</option>
                            <option value="Ostalo" @if($cm->noncompliance_source=="Ostalo"){{'selected'}}@endif>Ostalo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">Opis neusaglašenosti:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('Unesite opis neusaglašenosti')"
                        oninput="this.setCustomValidity('')" >{{$cm->noncompliance_description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">Uzrok neusaglašenosti:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('Unesite uzrok neusaglašenosti')"
                        oninput="this.setCustomValidity('')">{{$cm->noncompliance_cause}}</textarea>

                    </div>
                    <div class="form-group">
                        <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">Mera za otklanjanje neusaglašenosti:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('Unesite meru za otklanjanje neusaglašenosti')"
                        oninput="this.setCustomValidity('')">{{$cm->measure}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">Odobravanje mere:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
                            <option value="1"  @if($cm->measure_approval==1){{'selected'}}@endif>DA</option>
                            <option value="0"  @if($cm->measure_approval==0){{'selected'}}@endif>NE</option>
                        </select>
                    </div>
                    <div class="form-group" id="measure_reason_field" style="@if($cm->measure_approval){{'display: none'}}@endif">
                        <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">Razlog neodobravanja mere</label>
                        <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja')"
                        oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="measure_approval_reason" id="measure_approval_reason" value="{{$cm->measure_approval_reason}}">
                    </div>
                    <div class="form-group">
                        <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">Status mere:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
                            <option value="0" @if($cm->measure_status==0){{'selected'}}@endif>NE</option>
                            <option value="1" @if($cm->measure_status==1){{'selected'}}@endif >DA</option>
                        </select>
                    </div>
                    <div class="form-group" id="measure_effective_field" style="@if(!$cm->measure_status){{'display: none'}}@endif">
                        <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">Mera efektivna:</label>
                        <select oninvalid="this.setCustomValidity('Izaberite efektivnost mere')"
                        oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
                            <option value="">Izaberi...</option>
                            <option value="1" @if($cm->measure_effective==1){{'selected'}}@endif >DA</option>
                            <option value="0" @if($cm->measure_effective==0){{'selected'}}@endif>NE</option>
                        </select>
                    </div>
                </div>
            @endif

            <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
        </form>
    </div>

</x-app-layout>

<script>
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
</script>
