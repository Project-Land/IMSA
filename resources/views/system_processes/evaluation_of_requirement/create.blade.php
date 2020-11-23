<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Vrednovanje zakonskih i drugih zahteva - Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
            <a class="btn btn-light" href="{{ route('evaluation-of-requirements.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded" id="formDiv">

		<form action="{{ route('evaluation-of-requirements.store') }}" method="POST" autocomplete="off"  class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="year" class="block text-gray-700 text-sm font-bold mb-2">Nivo sa kojeg zahtev potiče</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="requirement_level" name="requirement_level" required oninvalid="this.setCustomValidity('Izaberite nivo')" oninput="this.setCustomValidity('')">
                        <option value="">Izaberite...</option>
                        <option value="Evropska Unija">Evropska Unija</option>
                        <option value="Država">Država</option>
                        <option value="Grad">Grad</option>
                        <option value="Lokalna samouprava">Lokalna samouprava</option>
                        <option value="Partner">Partner (kupac, dobavljač...)</option>
                        <option value="Ostalo">Ostalo</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="responsibility" class="block text-gray-700 text-sm font-bold mb-2">Naziv dokumenta/zakona, ili opis zahteva</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="document_name" id="document_name" required oninvalid="this.setCustomValidity('Popunite polje')" oninput="this.setCustomValidity('')" value="{{ old('document_name') }}">
                    @error('document_name')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="goal" class="block text-gray-700 text-sm font-bold mb-2">Ocena usaglašenosti</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="compliance" name="compliance" required oninvalid="this.setCustomValidity('Izaberite ocenu usaglašenosti')" oninput="this.setCustomValidity('')">
                        <option value="">Izaberite...</option>
                        <option value="1">Usaglašen</option>
                        <option value="0">Neusaglašen</option>
                    </select>
                    @error('compliance')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">Napomena</label>
                    <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="note" id="note" value="{{ old('note') }}">
                    @error('note')
					    <span class="text-red-700 italic text-sm">{{ $message }}</span>
				    @enderror
                </div>
            </div>

            <div id="divParent" style="background-color:#ebffe6;"></div>

            <div class="mb-4 mt-1">
                <button type="submit" class="w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline float-md-right">Kreiraj</button>
            </div>

        </form>
    </div>

</x-app-layout>

<script>
    let counter=1;

    function addCorrectiveMeasure(){
        const compliance=document.getElementById('compliance');
        const divParent=document.getElementById('divParent');

        if(compliance.value==1){
            divParent.innerHTML = "";
            console.log(compliance.value);
            return;
        }
        const divChild=document.createElement('div');
        const div=document.getElementById('divParent');

        divChild.innerHTML=
            `<div class="py-2 p-3">
            <h4 class="text-center my-3">Karton korektivne mere</h4>
            <div class="form-group">
                <label for="noncompliance_source[${counter}]" class="block text-gray-700 text-sm font-bold mb-2">Izvor informacije o neusaglašenostima:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="" required oninvalid="this.setCustomValidity('Izaberite izvor informacije o neusaglašenosti')"
                oninput="this.setCustomValidity('')">
                <option value="">Izaberi...</option>
                <option value="Eksterna provera">Eksterna provera</option>
                <option value="Interna provera">Interna provera</option>
                <option value="Preispitivanje ISM-a" >Preispitivanje ISM-a</option>
                <option value="Žalba" >Žalba</option>
                <option value="Ostalo">Ostalo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="noncompliance_description${counter}" class="block text-gray-700 text-sm font-bold mb-2">Opis neusaglašenosti:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description${counter}" name="noncompliance_description[${counter}]" required oninvalid="this.setCustomValidity('Unesite opis neusaglašenosti')"
                oninput="this.setCustomValidity('')" ></textarea>
            </div>
            <div class="form-group">
                <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">Uzrok neusaglašenosti:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause${counter}" name="noncompliance_cause[${counter}]" required oninvalid="this.setCustomValidity('Unesite uzrok neusaglašenosti')"
                oninput="this.setCustomValidity('')"></textarea>

            </div>
            <div class="form-group">
                <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">Mera za otklanjanje neusaglašenosti:</label>
                <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure${counter}" name="measure[${counter}]" required oninvalid="this.setCustomValidity('Unesite meru za otklanjanje neusaglašenosti')"
                oninput="this.setCustomValidity('')"></textarea>

            </div>
            <div class="form-group">
                <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">Odobravanje mere:</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval[${counter}]" id="measure_approval${counter}">
                    <option value="1" >DA</option>
                    <option value="0" >NE</option>
                </select>
            </div>
            <div class="form-group" id="measure_reason_field${counter}" style="display: none">
                <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">Razlog neodobravanja mere</label>
                <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja')"
                oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="measure_approval_reason[${counter}]" id="measure_approval_reason${counter}" >
            </div>
            <div class="form-group">
                <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">Da li je mera sprovedena?</label>
                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status[${counter}]" id="measure_status${counter}">
                    <option value="0" >NE</option>
                    <option value="1"  >DA</option>
                </select>
            </div>
            <div class="form-group" id="measure_effective_field${counter}" style="display: none">
                <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">Mera efektivna:</label>
                <select oninvalid="this.setCustomValidity('Izaberite efektivnost mere')"
                oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective[${counter}]" id="measure_effective${counter}" >
                <option value="">Izaberi...</option>
                        <option value="1">DA</option>
                        <option value="0">NE</option>
                    </select>
                </div>

                
            </div>
        `;

        div.append(divChild);

        document.getElementById('noncompliance_description' + counter).focus();

        let id = `#measure_approval${counter}`;
        let id_ms = `#measure_status${counter}`;
        let id_mef = `#measure_effective_field${counter}`;
        let id_mrf = `#measure_reason_field${counter}`;
        let id_mar = `#measure_approval_reason${counter}`;
        let id_me = `#measure_effective${counter}`;

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

        counter++;
    }

    document.getElementById('compliance').addEventListener('change',addCorrectiveMeasure);
</script>
