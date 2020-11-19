<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('standard_name').' - Izveštaj sa interne provere - Izmena' }}
        </h2>
    </x-slot>

    <div class="row">
    	<div class="col">
        	<a class="btn btn-light" href="{{ route('internal-check.index') }}"><i class="fas fa-arrow-left"></i> Nazad</a>
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
            <div class="alert alert-info alert-dismissible fade show">
                {{ Session::get('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

		<form id="internal_check_report_edit_form" action="{{ route('internal-check-report.update',$internalCheckReport->id) }}" method="POST" autocomplete="off" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="form-group col" >
                    <label for="checked_sector" class="block text-gray-700 text-sm font-bold mb-2">Proveravano područje</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="checked_sector" placeholder="" name="checked_sector" value="{{ $internalCheckReport->internalCheck->sector->name }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="standard" class="block text-gray-700 text-sm font-bold mb-2">Standard</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="standard" placeholder="" name="standard" value="{{ $internalCheckReport->internalCheck->standard->name }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="team_for_internal_check" class="block text-gray-700 text-sm font-bold mb-2">Tim za proveru</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="team_for_internal_check" placeholder="" name="team_for_internal_check" value="{{ $internalCheckReport->internalCheck->leaders }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="check_start" class="block text-gray-700 text-sm font-bold mb-2">Početak provere</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_start" placeholder="" name="check_start" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_start)) }}" readonly>
                </div>

                <div class="form-group col">
                    <label for="check_end" class="block text-gray-700 text-sm font-bold mb-2">Završetak provere</label>
                    <input type="text" class="bg-gray-200 appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="check_end" placeholder="" name="check_end" value="{{ date('d.m.Y', strtotime($internalCheckReport->internalCheck->planIp->check_end)) }}" readonly>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="specification" class="block text-gray-700 text-sm font-bold mb-2">Specifikacija dokumenata</label>
                    <textarea rows="3" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="specification" placeholder="" name="specification" value="{{ $internalCheckReport->specification }}" required oninvalid="this.setCustomValidity('Specifikacija nije popunjena')"
                        oninput="this.setCustomValidity('')">{{ $internalCheckReport->specification }}</textarea>
                    @error('specification')
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group mt-2" style="border-bottom:solid 2px gray;">
                <span class="btn btn-primary mb-2" id="addInc"><i class="fas fa-plus"></i> Dodaj neusaglašenost</span>
                <span id="addRecommendations" class="btn btn-primary mb-2"><i class="fas fa-plus"></i> Dodaj preporuku</span>
            </div>

            <div id="inconsistenciesDiv" class="row border-top mt-2 mb-2" style="background:#eeffe6;border-bottom:solid 2px gray;">
                @foreach($internalCheckReport->correctiveMeasures as $inc)
                    <div class="form-group col-6 mt-3">
                        <label for="inconsistencies" class="block text-gray-700 text-sm font-bold mb-2">Neusaglašenost</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="inconsistencies[{{ $inc->id }}]" required>{{ $inc->noncompliance_description }}</textarea>
                        @error('inconsistencies.'.$inc->id)
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror
                    <span data-toggle="tooltip" data-placement="top" title="Prikaz korektivne mere" class="text-blue-700 cursor-pointer hover:text-blue-500" onclick="showMeasure({{ $inc->id }})">{{ $inc->name }}</span>
                        <button data-toggle="tooltip" data-placement="top" title="Brisanje korektivne mere" class="deleteButton btn btn-danger float-right ml-2 mt-2"><i class="fas fa-trash"></i></button>
                        <a data-toggle="tooltip" data-placement="top" title="Izmena korektivne mere" class="btn btn-primary float-right ml-2 mt-2" href="{{ route('corrective-measures.edit', $inc->id) }}" ><i class="fas fa-edit"></i></a>
                    </div>
                @endforeach
            </div>

            <div id="recommendationsDiv"  class="row border-top mt-2">
                @foreach($internalCheckReport->recommendations as $rec)
                    <div class="form-group col-6 mt-3" id="recommendations[{{ $rec->id }}]">
                        <label for="recommendations" class="block text-gray-700 text-sm font-bold mb-2">Preporuka</label>
                        <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="recommendations[{{ $rec->id }}]" required>{{ $rec->description }}</textarea>
                        @error('recommendations.'.$rec->id)
                            <span class="text-red-700 italic text-sm">{{ $message }}</span>
                        @enderror
                        <button data-toggle="tooltip" data-placement="top" title="Brisanje preporuke" class="deleteButton btn btn-danger mt-1 float-right"><i class="fas fa-trash"></i></button>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <button type="submit" id="submitForm" class="float-right w-full md:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline" >Sačuvaj</button>
            </div>
        </form>

    </div>

    <script>

        let counter = 1;
        let coun = 1;

        function removeInput(){
            if(this.dataset.counter == 'counter'){}
            // counter--;
            if(this.dataset.counter == 'coun'){}
            // coun--;
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
            addNewRecommendations.title = "Brisanje preporuke";

            addNewRecommendations.innerHTML = '<i class="fas fa-trash"></i>';
            label.for = "newInputRecommendation"+coun;
            div.append(label);
            label.textContent = "Preporuka";
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

        let modal =
            `<div class="modal" id="kkm-1" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg mx-auto md:w-4/5 mt-1 md:p-10 sm:p-2 rounded" role="document">
                    <div class="modal-content rounded-0">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bold">Karton Korektivne Mere</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" autocomplete="off" action="{{ route('corrective-measures.store-from-icr') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                @csrf

                                <input type="hidden" name="sector_id" value="{{ $internalCheckReport->internalCheck->sector->id }}">
                                <input type="hidden" name="standard_id" value="{{ $internalCheckReport->internalCheck->standard->id }}">

                                <input type="hidden" name="correctiveMeasureable_id" value="{{ $internalCheckReport->id }}">
                                <input type="hidden" name="correctiveMeasureable_type" value="{{ 'App\\\Models\\\InternalCheckReport' }}">

                                <div class="mb-4">
                                    <label for="noncompliance_source" class="block text-gray-700 text-sm font-bold mb-2">Izvor informacije o neusaglašenostima:</label>
                                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="noncompliance_source" id="noncompliance_source"
                                    required oninvalid="this.setCustomValidity('Izvor informacije o neusaglašenostima nije izabran')"
                                    oninput="this.setCustomValidity('')">
                                        <option value="Interna provera" selected>Interna provera</option>
                                    </select>
                                    @error('noncompliance_source')
                                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="noncompliance_description" class="block text-gray-700 text-sm font-bold mb-2">Opis neusaglašenosti:</label>
                                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_description" name="noncompliance_description" required oninvalid="this.setCustomValidity('Opis neusaglašenosti nije popunjen')"
                                    oninput="this.setCustomValidity('')">{{ old('noncompliance_description') }}</textarea>
                                    @error('noncompliance_description')
                                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="noncompliance_cause" class="block text-gray-700 text-sm font-bold mb-2">Uzrok neusaglašenosti:</label>
                                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="noncompliance_cause" name="noncompliance_cause" required oninvalid="this.setCustomValidity('Uzrok neusaglašenosti nije popunjen')"
                                    oninput="this.setCustomValidity('')">{{ old('noncompliance_cause') }}</textarea>
                                    @error('noncompliance_cause')
                                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="measure" class="block text-gray-700 text-sm font-bold mb-2">Mera za otklanjanje neusaglašenosti:</label>
                                    <textarea class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="measure" name="measure" required oninvalid="this.setCustomValidity('Mera za otklanjanje neusaglašenosti nije popunjena')"
                                    oninput="this.setCustomValidity('')">{{ old('measure') }}</textarea>
                                    @error('measure')
                                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="measure_approval" class="block text-gray-700 text-sm font-bold mb-2">Odobravanje mere:</label>
                                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_approval" id="measure_approval">
                                        <option value="1" {{ old('measure_approval') == '1' ? "selected" : "" }} >DA</option>
                                        <option value="0" {{ old('measure_approval') == '0' ? "selected" : "" }} >NE</option>
                                    </select>
                                </div>

                                <div class="mb-4" id="measure_reason_field" style="display: none">
                                    <label for="measure_approval_reason" class="block text-gray-700 text-sm font-bold mb-2" >Razlog neodobravanja mere</label>
                                    <input oninvalid="this.setCustomValidity('Popunite razlog neodobravanja mere')"
                                    oninput="this.setCustomValidity('')" type="text" class="appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  name="measure_approval_reason" id="measure_approval_reason" >
                                    @error('measure_approval_reason')
                                        <span class="text-red-700 italic text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="measure_status" class="block text-gray-700 text-sm font-bold mb-2">Status mere:</label>
                                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_status" id="measure_status">
                                        <option value="0" {{ old('measure_status') == '0' ? "selected" : "" }} >NE</option>
                                        <option value="1" {{ old('measure_status') == '1' ? "selected" : "" }} >DA</option>
                                    </select>
                                </div>

                                <div class="mb-4" id="measure_effective_field" style="display: none">
                                    <label for="measure_effective" class="block text-gray-700 text-sm font-bold mb-2">Mera efektivna:</label>
                                    <select oninvalid="this.setCustomValidity('Izaberite efektivnost mere')"
                                    oninput="this.setCustomValidity('')" class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="measure_effective" id="measure_effective" >
                                        <option value="">Izaberi...</option>
                                        <option value="1" {{ old('measure_effective') == '1' ? "selected" : "" }} >DA</option>
                                        <option value="0" {{ old('measure_effective') == '0' ? "selected" : "" }} >NE</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-1/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline">Kreiraj</button>
                                <button type="button" class="w-1/4 float-right bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 focus:outline-none focus:shadow-outline rounded-0" data-dismiss="modal">Odustani</button>

                            </form>

                        </div>
                    </div>
                </div>
        </div>`;

        $('body').append(modal);

        $('#addInc').click( () => {
            $('#kkm-1').modal();
            $('#noncompliance_description').focus();
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
                let modal = `<div class="modal fade" id="showData-${ id }" tabindex="-1" role="dialog">
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
                                                <div class="col-sm-5 mt-1 border-bottom font-weight-bold"><p>Datum kreiranja</p></div>
                                                <div class="col-sm-7 mt-1 border-bottom"><p>${ new Date(response.data.created_at).toLocaleString('sr-SR', { timeZone: 'CET' }) }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Sistem menadžment</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.standard.name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Izvor neusaglašenosti</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_source }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Organizaciona celina</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.sector.name }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Opis neusaglašenosti</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_description }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Uzrok neusaglašenosti</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.noncompliance_cause }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Mera za otklanjanje neusaglašenosti</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Mera odobrena</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval == 1 ? "Odobrena" : "Neodobrena" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Razlog neodobravanja mere</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_reason == null ? "/" : response.data.measure_approval_reason }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Datum odobravanja mere</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_approval_date != null ? new Date(response.data.measure_approval_date).toLocaleDateString('sr-SR', { timeZone: 'CET' }) : "/" }</p></div>
                                                <div class="col-sm-5 mt-3 border-bottom font-weight-bold"><p>Mera efektivna</p></div>
                                                <div class="col-sm-7 mt-3 border-bottom"><p>${ response.data.measure_effective != null ? response.data.measure_effective == 1 ? "Efektivna" : "Neefektivna" : "/" }</p></div>
                                            </div>
                                        </div>
                                        <div class="px-6 py-4 bg-gray-100 text-right">
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" data-dismiss="modal">Zatvori</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                $("body").append(modal);
                $('#showData-'+id).modal();
            })
            .catch((error) => {
                console.log(error)
            })
        }

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>

</x-app-layout>
