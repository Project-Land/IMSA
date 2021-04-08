<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izveštaj sa interne provere') }} - {{ __('Kreiranje') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('internal-check.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
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
                    <label for="checked_sector" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Proveravano područje') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_sector" name="checked_sector" value="@foreach(collect($internalCheck->sectors) as $sector_id){{\App\Models\Sector::find($sector_id)->name.', '}} @endforeach" readonly>
                </div>

                <div class="form-group col">
                    <label for="standard" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Sistem menadžment') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="standard" name="standard" value="{{$internalCheck->standard->name}}" readonly>
                </div>

                <div class="form-group col">
                    <label for="team_for_internal_check" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Tim za proveru') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="team_for_internal_check" name="team_for_internal_check" value="{{$internalCheck->leaders}}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="check_start" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Početak provere') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_start" name="check_start" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_start)) }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="check_end" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Završetak provere') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_end" name="check_end" value="{{ date('d.m.Y H:i', strtotime($internalCheck->planIp->check_end)) }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="specification" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Specifikacija dokumenata') }}</label>
                    <textarea rows="3" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="specification" name="specification" required oninvalid="this.setCustomValidity('{{ __("Specifikacija nije popunjena") }}')"
                oninput="this.setCustomValidity('')">{{old('specification')}}</textarea>
                    @error('specification')
                        <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="inconsistencies" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Neusaglašenosti') }}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="inconsistencies" name="inconsistencies" >
                        <option value="0">{{ __('Ne') }}</option>
                        <option value="1">{{ __('Da') }}</option>
                    </select>
                </div>

                <div class="form-group col">
                    <label for="recommendations" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Preporuke') }}</label>
                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="recommendations" name="recommendations">
                        <option value="0">{{ __('Ne') }}</option>
                        <option value="1">{{ __('Da') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="float-right w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Sačuvaj') }}</button>
            </div>
        </form>
    </div>

    @push('page-scripts')
        <script>
            var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
            $.datetimepicker.setLocale(lang);

            let counter = 1;

            function removeInput(){
                if(this.dataset.counter == 'counter'){}
                if(this.dataset.counter == 'coun'){}
                this.closest("div").remove();
                $('body>.tooltip').remove();
                let inputs = document.querySelectorAll("[id^='newInputRecommendationDiv']");
                if(!inputs.length){
                    document.getElementById('recommendations').value = '0';
                    document.getElementById('AddRecommedationsButton').remove();
                }
            }

            const addSecondInconsistencies = function() {
                document.getElementById("button"+(counter-1)).style="display:none;";
                const newInput=document.createElement('textarea');
                const div=document.createElement('div');
                const label=document.createElement('label');
                const addNewInconsistencies=document.createElement('button');
                let deletebutton=document.createElement('button');

                deletebutton.classList="btn btn-danger my-2 mx-4 float-right";
                deletebutton.setAttribute("data-counter", "counter");
                deletebutton.id="button"+counter;
                deletebutton.innerHTML='<i class="fas fa-trash"></i>';
                deletebutton.addEventListener('click',removeInput);

                deletebutton.setAttribute('data-toggle', 'tooltip');
                deletebutton.setAttribute('data-placement', 'top');
                deletebutton.title="{{ __('Brisanje korektivne mere') }}";

                addNewInconsistencies.classList="btn btn-primary my-2 mx-4";
                addNewInconsistencies.type="button";
                addNewInconsistencies.id="button"+counter;
                addNewInconsistencies.addEventListener('click',addSecondInconsistencies);
                addNewInconsistencies.textContent="{{ __('Dodaj još jednu neusaglašenost') }}"
                newInput.id='newInput'+counter;
                newInput.name='newInput'+counter;
                newInput.type='text';
                newInput.classList="d-none block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500";
                newInput.textContent=counter;
                div.append(newInput);
                div.classList="form-group mt-3 bg-gray-300 shadow-md rounded mt-5";
                div.id="newInputDiv"+counter;
                document.getElementById("newInputDiv"+(counter-1)).after(div);

                const div2=document.createElement('div');
                div2.innerHTML+=
                    `<div class="py-2 px-4" style="background-color:#ebffe6;">
                    <h4 class="text-center my-2">{{ __('Karton korektivne mere') }}</h4>
                    <div class="form-group">
                        <label for="noncompliance_source[${counter}]" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima') }}:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" value="" required oninvalid="this.setCustomValidity('{{ __("Izaberite izvor informacije o neusaglašenosti") }}')"
                        oninput="this.setCustomValidity('')" readonly>
                            <option value="Interna provera" selected>{{ __('Interna provera') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noncompliance_description${counter}" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti') }}:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description${counter}" name="noncompliance_description[${counter}]" required oninvalid="this.setCustomValidity('{{ __("Unesite opis neusaglašenosti") }}')"
                        oninput="this.setCustomValidity('')" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti') }}:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause${counter}" name="noncompliance_cause[${counter}]" required oninvalid="this.setCustomValidity('{{ __("Unesite uzrok neusaglašenosti") }}')"
                        oninput="this.setCustomValidity('')"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti') }}:</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure${counter}" name="measure[${counter}]" required oninvalid="this.setCustomValidity('{{ __("Unesite meru za otklanjanje neusaglašenosti") }}')"
                        oninput="this.setCustomValidity('')"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje mere') }}:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval[${counter}]" id="measure_approval${counter}">
                            <option value="1">{{ __('Da') }}</option>
                            <option value="0">{{ __('Ne') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deadline_date${counter}" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju korektivne mere') }}:</label>
                        <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline_date${counter}" name="deadline_date[${counter}]" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
                    </div>
                    <div class="form-group" id="measure_reason_field${counter}" style="display: none">
                        <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Razlog neodobravanja mere') }}</label>
                        <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja')"
                        oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="measure_approval_reason[${counter}]" id="measure_approval_reason${counter}" >
                    </div>
                    <div class="form-group">
                        <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je mera sprovedena?') }}:</label>
                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status[${counter}]" id="measure_status${counter}">
                            <option value="0">{{ __('Ne') }}</option>
                            <option value="1">{{ __('Da') }}</option>
                        </select>
                    </div>
                    <div class="form-group" id="measure_effective_field${counter}" style="display: none">
                        <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna') }}:</label>
                        <select oninvalid="this.setCustomValidity('{{ __("Izaberite efektivnost mere") }}')"
                        oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective[${counter}]" id="measure_effective${counter}" >
                            <option value="">{{ __('Izaberi') }}...</option>
                            <option value="1">{{ __('Da') }}</option>
                            <option value="0">{{ __('Ne') }}</option>
                        </select>
                    </div>
                    </div>`;
                div.append(div2);

                div.append(addNewInconsistencies);
                div.append(deletebutton);
                document.getElementById('noncompliance_description' + counter).focus();

                let id=`#measure_approval${counter}`;
                let id_ms=`#measure_status${counter}`;
                let id_mef=`#measure_effective_field${counter}`;
                let id_mrf=`#measure_reason_field${counter}`;
                let id_mar=`#measure_approval_reason${counter}`;
                let id_me=`#measure_effective${counter}`;

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

                $('#deadline_date'+counter).datetimepicker({
                    timepicker: false,
                    format: 'd.m.Y',
                    minDate: 0,
                    dayOfWeekStart: 1,
                    scrollInput: false
                });

                counter++;

                $('[data-toggle="tooltip"]').tooltip();
            }

            const form = document.getElementById('internal_check_report_create_form');
            const inconsistencies = document.getElementById('inconsistencies');
            const recommendations = document.getElementById('recommendations');

            const addInput = function() {
                let inconsistencies = document.getElementById('inconsistencies');
                const form = document.getElementById('internal_check_report_create_form');
                let deletebutton = document.createElement('button');

                deletebutton.classList = "btn btn-danger my-2 mx-4 float-right";
                deletebutton.setAttribute("data-counter", "counter");
                deletebutton.id = "button" + counter;
                deletebutton.innerHTML='<i class="fas fa-trash"></i>';
                deletebutton.setAttribute('data-toggle', 'tooltip');
                deletebutton.setAttribute('data-placement', 'top');
                deletebutton.title="{{ __('Brisanje korektivne mere') }}";
                deletebutton.addEventListener('click', removeInput);

                if(inconsistencies.value == 1){
                    const newInput=document.createElement('textarea');
                    const div=document.createElement('div');

                    const label=document.createElement('label');
                    const addNewInconsistencies=document.createElement('button');
                    addNewInconsistencies.classList="btn btn-primary my-2 mx-4";
                    addNewInconsistencies.type="button";
                    addNewInconsistencies.id="button"+counter;
                    addNewInconsistencies.addEventListener('click',addSecondInconsistencies);
                    addNewInconsistencies.textContent="{{ __('Dodaj još jednu neusaglašenost') }}"
                    newInput.id='newInput'+counter;
                    newInput.name='newInput'+counter;
                    newInput.type='text';
                    newInput.classList="d-none block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500";
                    newInput.textContent=counter;
                    div.append(newInput);
                    div.classList="form-group mt-3 bg-gray-300 shadow-md rounded mt-5";
                    div.id="newInputDiv"+counter;

                    const div2 = document.createElement('div');

                    div2.innerHTML+=
                        `<div class="py-2 px-4" style="background-color:#ebffe6;">
                        <h4 class="text-center my-2">{{ __('Karton korektivne mere') }}</h4>
                        <div class="form-group">
                            <label for="noncompliance_source[${ counter }]" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima') }}:</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="noncompliance_source[${counter}]" name="noncompliance_source[${counter}]" required oninvalid="this.setCustomValidity('Izaberite izvor informacije o neusaglašenosti')"
                            oninput="this.setCustomValidity('')" readonly>
                                <option value="Interna provera" selected>{{ __('Interna provera') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti') }}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description${counter}" name="noncompliance_description[${counter}]" required oninvalid="this.setCustomValidity('Unesite opis neusaglašenosti')"
                            oninput="this.setCustomValidity('')"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti') }}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause${counter}" name="noncompliance_cause[${counter}]" required oninvalid="this.setCustomValidity('Unesite uzrok neusaglašenosti')"
                            oninput="this.setCustomValidity('')"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti') }}:</label>
                            <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure${counter}" name="measure[${counter}]" required oninvalid="this.setCustomValidity('Unesite meru za otklanjanje neusaglašenosti')"
                            oninput="this.setCustomValidity('')"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje mere') }}:</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval[${counter}]" id="measure_approval${counter}">
                                <option value="1">{{ __('Da') }}</option>
                                <option value="0">{{ __('Ne') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deadline_date${counter}" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju korektivne mere') }}:</label>
                            <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline_date${counter}" name="deadline_date[${counter}]" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
                        </div>
                        <div class="form-group" id="measure_reason_field${counter}" style="display: none">
                            <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Razlog neodobravanja mere') }}</label>
                            <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja')"
                            oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight" name="measure_approval_reason[${counter}]" id="measure_approval_reason${counter}" >
                        </div>
                        <div class="form-group">
                            <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Status mere') }}:</label>
                            <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status[${counter}]" id="measure_status${counter}">
                                <option value="0">{{ __('Ne') }}</option>
                                <option value="1">{{ __('Da') }}</option>
                            </select>
                        </div>
                        <div class="form-group" id="measure_effective_field${counter}" style="display: none">
                            <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna') }}:</label>
                            <select oninvalid="this.setCustomValidity('{{ __("Izaberite efektivnost mere") }}')"
                            oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight " name="measure_effective[${counter}]" id="measure_effective${counter}">
                                <option value="">{{ __('Izaberi') }}...</option>
                                <option value="1">{{ __('Da') }}</option>
                                <option value="0">{{ __('Ne') }}</option>
                            </select>
                        </div>
                    </div>`;

                    div.append(div2);
                    inconsistencies.after(div);
                    div.append(addNewInconsistencies);
                    div.append(deletebutton);
                    document.getElementById('noncompliance_description' + counter).focus();

                    let id=`#measure_approval${counter}`;
                    let id_ms=`#measure_status${counter}`;
                    let id_mef=`#measure_effective_field${counter}`;
                    let id_mrf=`#measure_reason_field${counter}`;
                    let id_mar=`#measure_approval_reason${counter}`;
                    let id_me=`#measure_effective${counter}`;

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

                    $('#deadline_date'+counter).datetimepicker({
                        timepicker: false,
                        format: 'd.m.Y',
                        minDate: 0,
                        dayOfWeekStart: 1,
                        scrollInput: false
                    });

                    counter++;

                    $('[data-toggle="tooltip"]').tooltip();
                }
                else{
                    let newInputs = document.querySelectorAll("[id^='newInputDiv']");
                    for(let input of newInputs){
                        input.remove();
                        counter = 1;
                    }
                }
            }

            let coun=1;

            const addSecondRecommedation = function() {
                if(document.getElementById("newInputRecommendation"+(coun-1)).value===""){
                    return;
                }

                const newInput=document.createElement('textarea');
                const div=document.createElement('div');
                const label=document.createElement('label');
                const deletebutton=document.createElement('button');
                deletebutton.classList="btn btn-danger mt-3 float-right";
                deletebutton.setAttribute("data-counter", "coun");
                deletebutton.id="buttonRecommedations"+coun;
                deletebutton.innerHTML='<i class="fas fa-trash"></i>';
                deletebutton.setAttribute('data-toggle', 'tooltip');
                deletebutton.setAttribute('data-placement', 'top');
                deletebutton.title="{{ __('Brisanje preporuke') }}";
                deletebutton.addEventListener('click',removeInput);

                label.for="newInputRecommendation"+coun;
                div.append(label);
                label.textContent="{{ __('Upiši preporuku') }}";
                newInput.id='newInputRecommendation'+coun;
                newInput.name='newInputRecommendation'+coun;
                newInput.type='text';
                newInput.classList="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline";
                newInput.required=true;
                newInput.oninvalid=function(){this.setCustomValidity("{{ __('Unesite preporuku') }}");}
                newInput.oninput=function(){this.setCustomValidity('');}
                div.append(newInput);
                div.append(deletebutton);
                div.classList="form-group mt-3";
                div.id="newInputRecommendationDiv"+coun;
                document.getElementById("newInputRecommendationDiv"+(coun-1)).after(div);

                coun++;
                $('[data-toggle="tooltip"]').tooltip();
                newInput.focus();
            }


            const addInputRecommedation = function() {

                if(recommendations.value == 1){

                    const newInput = document.createElement('textarea');
                    const div = document.createElement('div');
                    const label = document.createElement('label');
                    const addNewRecommendations = document.createElement('button');

                    addNewRecommendations.classList = "btn btn-primary";
                    addNewRecommendations.type = "button";
                    addNewRecommendations.id = "AddRecommedationsButton";
                    addNewRecommendations.addEventListener('click', addSecondRecommedation);
                    addNewRecommendations.textContent = "{{ __('Dodaj još jednu preporuku') }}"

                    const deletebutton = document.createElement('button');
                    deletebutton.classList = "btn btn-danger my-2 float-right";
                    deletebutton.setAttribute("data-counter", "coun");
                    deletebutton.id = "buttonRecommedations" + coun;
                    deletebutton.innerHTML = '<i class="fas fa-trash"></i>';
                    deletebutton.setAttribute('data-toggle', 'tooltip');
                    deletebutton.setAttribute('data-placement', 'top');
                    deletebutton.title = "{{ __('Brisanje preporuke') }}";
                    deletebutton.addEventListener('click', removeInput);

                    label.for = "newInputRecommendation" + coun;
                    div.append(label);
                    label.textContent = "{{ __('Upiši preporuku') }}";
                    newInput.id = 'newInputRecommendation' + coun;
                    newInput.name = 'newInputRecommendation' + coun;
                    newInput.type = 'text';
                    newInput.classList = "appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline";
                    newInput.required = true;
                    newInput.oninvalid = function(){ this.setCustomValidity("{{ __('Unesite preporuku') }}"); }
                    newInput.oninput = function(){ this.setCustomValidity(''); }

                    div.append(newInput);
                    div.append(deletebutton);

                    div.classList="form-group mt-3";
                    div.id="newInputRecommendationDiv"+coun;
                    recommendations.after(div);

                    div.after(addNewRecommendations);

                    coun++;
                    $('[data-toggle="tooltip"]').tooltip();
                    newInput.focus();
                }
                else{
                    document.getElementById('AddRecommedationsButton').remove();
                    let newInputs = document.querySelectorAll("[id^='newInputRecommendationDiv']");
                    for(let input of newInputs){
                        input.remove();
                        coun = 1;
                    }
                }
            }

            inconsistencies.addEventListener('change', addInput);
            recommendations.addEventListener('change', addInputRecommedation);

            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });

        </script>
    @endpush

</x-app-layout>
