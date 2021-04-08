<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl mb-0 text-gray-800 leading-tight">
            {{ session('standard_name') }} - {{ __('Izveštaj sa interne provere') }} - {{ __('Izmena') }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('internal-check.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Nazad') }}</a>
     	</div>
    </div>

    <div class="mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-700 italic text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('status'))
            <x-alert :type="Session::get('status')[0]" :message="__(Session::get('status')[1])"/>
        @endif

		<form id="internal_check_report_edit_form" action="{{ route('internal-check-report.update', $internalCheckReport->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="form-group col" >
                    <label for="checked_sector" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Proveravano područje') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_sector" name="checked_sector" value="@foreach(collect($internalCheckReport->internalCheck->sectors) as $sector_id){{\App\Models\Sector::find($sector_id)->name.', '}} @endforeach" readonly>
                </div>

                <div class="form-group col">
                    <label for="standard" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Sistem menadžment') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="standard" name="standard" value="{{ $internalCheckReport->internalCheck->standard->name }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="team_for_internal_check" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Tim za proveru') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="team_for_internal_check" name="team_for_internal_check" value="{{ $internalCheckReport->internalCheck->leaders }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="check_start" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Početak provere') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_start" name="check_start" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_start)) }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="check_end" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Završetak provere') }}</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_end" name="check_end" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_end)) }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="specification" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Specifikacija dokumenata') }}</label>
                    <textarea rows="3" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="specification" name="specification" value="{{ $internalCheckReport->specification }}" required oninvalid="this.setCustomValidity('{{ __("Specifikacija nije popunjena") }}')"
                        oninput="this.setCustomValidity('')">{{ $internalCheckReport->specification }}</textarea>
                    @error('specification')
                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group mt-2">
                <span class="btn btn-primary mb-2" id="addInc"><i class="fas fa-plus"></i> {{ __('Dodaj neusaglašenost') }}</span>
                <span id="addRecommendations" class="btn btn-primary mb-2 mx-2"><i class="fas fa-plus"></i> {{ __('Dodaj preporuku') }}</span>
            </div>

            <div id="inconsistenciesDiv" class="row border-t-2 border-b-2 border-gray-500 my-2 mx-0" style="background:#eeffe6;">
                @foreach($internalCheckReport->correctiveMeasures as $inc)
                    <div class="form-group col-6 mt-3">
                        <label for="inconsistencies" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Neusaglašenost') }}</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="inconsistencies[{{ $inc->id }}]" required>{{ $inc->noncompliance_description }}</textarea>
                        @error('inconsistencies.'.$inc->id)
                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                        @enderror
                    <span data-toggle="tooltip" data-placement="top" title="{{ __('Prikaz korektivne mere') }}" class="text-blue-700 cursor-pointer hover:text-blue-500" onclick="showMeasure({{ $inc->id }})">{{ $inc->name }}</span>
                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje korektivne mere') }}" class="deleteButton btn btn-danger float-right ml-2 mt-2" onclick="$('body>.tooltip').remove();"><i class="fas fa-trash"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="{{ __('Izmena korektivne mere') }}" class="btn btn-primary float-right ml-2 mt-2" href="{{ route('corrective-measures.edit', $inc->id) }}" ><i class="fas fa-edit"></i></a>
                    </div>
                @endforeach
            </div>

            <div id="recommendationsDiv" class="row mt-2">
                @foreach($internalCheckReport->recommendations as $rec)
                    <div class="form-group col-6 mt-3" id="recommendations[{{ $rec->id }}]">
                        <label for="recommendations" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Preporuka') }}</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="recommendations[{{ $rec->id }}]" required>{{ $rec->description }}</textarea>
                        @error('recommendations.'.$rec->id)
                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                        @enderror
                        <button data-toggle="tooltip" data-placement="top" title="{{ __('Brisanje preporuke') }}" class="deleteButton btn btn-danger mt-1 float-right" onclick="$('body>.tooltip').remove();"><i class="fas fa-trash"></i></button>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <button type="submit" id="submitForm" class="float-right w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Sačuvaj') }}</button>
            </div>
        </form>

    </div>

    @push('page-scripts')
        <script>
            let counter = 1;
            let coun = 1;

            function removeInput(){
                if(this.dataset.counter == 'counter'){}
                if(this.dataset.counter == 'coun'){}
                this.closest("div").remove();
            }

            const form = document.getElementById('internal_check_report_edit_form');
            const recommendations = document.getElementById('addRecommendations');
            const recommendationsDiv = document.getElementById('recommendationsDiv');

            const addInputRecommedation = function() {

                if(document.getElementById("newInputRecommendation" + (coun-1)) != null){
                    if(document.getElementById("newInputRecommendation" + (coun-1)).value === "")
                        return;
                }

                const newInput = document.createElement('textarea');
                const div = document.createElement('div');
                const label = document.createElement('label');
                const addNewRecommendations = document.createElement('button');

                addNewRecommendations.classList = "btn btn-danger mt-1 float-right";
                addNewRecommendations.setAttribute("data-counter", "coun");
                addNewRecommendations.id = "buttonRecommedations" + coun;
                addNewRecommendations.setAttribute('data-toggle', 'tooltip');
                addNewRecommendations.setAttribute('data-placement', 'top');
                addNewRecommendations.title = "{{ __('Brisanje preporuke') }}";
                addNewRecommendations.onclick = function(){$('body>.tooltip').remove();};
                addNewRecommendations.innerHTML = '<i class="fas fa-trash"></i>';
                label.for = "newInputRecommendation"+coun;
                div.append(label);
                label.textContent = "{{ __('Preporuka') }}";
                label.classList = "mt-3 block text-gray-700 text-sm font-bold mb-2";
                newInput.id = 'newInputRecommendation' + coun;
                newInput.name = 'newInputRecommendation' + coun;
                newInput.type = 'text';
                newInput.style = "background:#dbffe5;"
                newInput.required = true;
                newInput.classList = "appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline";
                addNewRecommendations.addEventListener('click', removeInput);
                div.append(newInput);
                div.classList = "form-group col-6";
                div.id = "newInputRecommendationDiv" + coun;
                recommendations.after(div);
                div.append(addNewRecommendations);
                recommendationsDiv.append(div);
                newInput.focus();
                coun++;
                $('[data-toggle="tooltip"]').tooltip();
            }

            recommendations.addEventListener('click', addInputRecommedation);

            document.querySelectorAll('.deleteButton').forEach( button => {
                button.addEventListener('click', removeInput);
            });

            let modal =`
                <div class="modal" id="kkm-1" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded" role="document">
                        <div class="modal-content rounded-0">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-bold">{{ __('Karton Korektivne Mere') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" autocomplete="off" action="{{ route('corrective-measures.store-from-icr') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                    @csrf

                                    <input type="hidden" name="standard_id" value="{{ $internalCheckReport->internalCheck->standard->id }}">

                                    <input type="hidden" name="correctiveMeasureable_id" value="{{ $internalCheckReport->id }}">
                                    <input type="hidden" name="correctiveMeasureable_type" value="{{ 'App\\\Models\\\InternalCheckReport' }}">

                                    <div class="mb-4">
                                        <label for="sector_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Organizaciona celina u kojoj je utvrđena neusaglašenost') }}:</label>
                                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="sector_id" id="sector_id" required oninvalid="this.setCustomValidity('{{ __("Organizaciona celina u kojoj je utvrđena neusaglašenost nije izabrana") }}')"
                                        oninput="this.setCustomValidity('')">
                                            <option value="">{{ __('Izaberi') }}...</option>
                                            @foreach(\App\Models\Sector::find($internalCheckReport->internalCheck->sectors) as $sector)
                                                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sector_id')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Izvor informacije o neusaglašenostima') }}:</label>
                                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="noncompliance_source" id="noncompliance_source"
                                        required oninvalid="this.setCustomValidity('__("Izvor informacije o neusaglašenostima nije izabran")')"
                                        oninput="this.setCustomValidity('')">
                                            <option value="Interna provera" selected>{{ __('Interna provera') }}</option>
                                        </select>
                                        @error('noncompliance_source')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Opis neusaglašenosti') }}:</label>
                                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('{{ __("Opis neusaglašenosti nije popunjen") }}')"
                                        oninput="this.setCustomValidity('')">{{ old('noncompliance_description') }}</textarea>
                                        @error('noncompliance_description')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Uzrok neusaglašenosti') }}:</label>
                                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('{{ __("Uzrok neusaglašenosti nije popunjen") }}')"
                                        oninput="this.setCustomValidity('')">{{ old('noncompliance_cause') }}</textarea>
                                        @error('noncompliance_cause')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera za otklanjanje neusaglašenosti') }}:</label>
                                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('{{ __("Mera za otklanjanje neusaglašenosti nije popunjena") }}')"
                                        oninput="this.setCustomValidity('')">{{ old('measure') }}</textarea>
                                        @error('measure')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Odobravanje mere') }}:</label>
                                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
                                            <option value="1" {{ old('measure_approval') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
                                            <option value="0" {{ old('measure_approval') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="deadline_date" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Rok za realizaciju korektivne mere') }}:</label>
                                        <input type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deadline_date" name="deadline_date" value="{{ old('deadline_date') }}" required oninvalid="this.setCustomValidity('{{__("Izaberite datum")}}')" oninput="this.setCustomValidity('')" onchange="this.setCustomValidity('')" placeholder="xx.xx.xxxx">
                                        @error('deadline_date')
                                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4" id="measure_reason_field" style="display: none">
                                        <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2" >{{ __('Razlog neodobravanja mere') }}</label>
                                        <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja mere')"
                                        oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="measure_approval_reason" id="measure_approval_reason" >
                                        @error('measure_approval_reason')
                                            <span class="text-red-700 italic text-sm">{{ __($message) }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Da li je mera sprovedena?') }}:</label>
                                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
                                            <option value="0" {{ old('measure_status') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
                                            <option value="1" {{ old('measure_status') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
                                        </select>
                                    </div>

                                    <div class="mb-4" id="measure_effective_field" style="display: none">
                                        <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Mera efektivna') }}:</label>
                                        <select oninvalid="this.setCustomValidity('Izaberite efektivnost mere')"
                                        oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
                                            <option value="">{{ __('Izaberi') }}...</option>
                                            <option value="1" {{ old('measure_effective') == '1' ? "selected" : "" }} >{{ __('Da') }}</option>
                                            <option value="0" {{ old('measure_effective') == '0' ? "selected" : "" }} >{{ __('Ne') }}</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="w-1/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">{{ __('Kreiraj') }}</button>
                                    <button type="button" class="w-1/4 float-right bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline rounded-0" data-dismiss="modal">{{ __('Odustani') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modal);

            $('#addInc').click( () => {
                $('#kkm-1').modal();
                $('#noncompliance_description').focus();
            });

                var lang = document.getElementsByTagName('html')[0].getAttribute('lang');
            $.datetimepicker.setLocale(lang);

            $('#deadline_date').datetimepicker({
                timepicker: false,
                format: 'd.m.Y',
                minDate: 0,
                dayOfWeekStart: 1,
                scrollInput: false
            });

            $('#measure_approval').change( () => {
                if($('#measure_approval').val() == 0){
                    $('#measure_reason_field').css('display', '');
                    $('#measure_approval_reason').attr('required', true);
                }
                else{
                    $('#measure_reason_field').css('display', 'none');
                    $('#measure_reason').val('');
                    $('#measure_approval_reason').attr('required', false);
                }
            })

            $('#measure_status').change( () => {
                if($('#measure_status').val() == 1){
                    $('#measure_effective_field').css('display', '');
                    $('#measure_effective').attr('required', true);
                }
                else{
                    $('#measure_effective_field').css('display', 'none');
                    $('#measure_effective').attr('required', false);
                }
            })

            function showMeasure(id){
                axios.get('/corrective-measures/'+id)
                .then((response) => {
                    let modal = `
                        <div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h5 class="modal-title font-weight-bold">${ response.data.name }</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-sm">
                                        <div class="row">
                                            <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>{{ __('Datum kreiranja') }}</p></div>
                                            <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.created_at).toLocaleDateString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Sistem menadžment') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.standard.name }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Izvor neusaglašenosti') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>{{ __('${ response.data.noncompliance_source }') }}</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Organizaciona celina') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.sector.name }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Opis neusaglašenosti') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_description }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Uzrok neusaglašenosti') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_cause }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera za otklanjanje neusaglašenosti') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Rok za realizaciju korektivne mere') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.deadline_date != null ? new Date(response.data.deadline_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera odobrena') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval == 1 ? "{{ __('Odobrena') }}" : "{{ __('Neodobrena') }}" }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Razlog neodobravanja mere') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_reason == null ? "/" : response.data.measure_approval_reason }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Datum odobravanja mere') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_date != null ? new Date(response.data.measure_approval_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Mera efektivna') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_effective != null ? response.data.measure_effective == 1 ? "{{ __('Efektivna') }}" : "{{ __('Neefektivna') }}" : "/" }</p></div>
                                            <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>{{ __('Kreirao') }}</p></div>
                                            <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.user.name }</p></div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-100 text-right">
                                        <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">{{ __('Zatvori') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $("body").append(modal);
                    $('#showData-'+id).modal();
                })
                .catch((error) => {
                    console.log(error)
                })
            }

            $('[data-toggle="tooltip"]').tooltip();

        </script>
    @endpush

</x-app-layout>
